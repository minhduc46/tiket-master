<?php
namespace app\controllers;

use app\models\Event;
use app\models\Order;
use app\models\Price;
use app\models\User;
use navatech\role\filters\RoleFilter;
use Yii;
use yii\data\ArrayDataProvider;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\Cookie;

/**
 * @ControllerName Thống kê
 */
class StatisticController extends Controller {

	/**
	 * @inheritdoc
	 */
	public function behaviors() {
		return [
			'verbs' => [
				'class'   => VerbFilter::className(),
				'actions' => [
					'delete' => ['POST'],
				],
			],
			'role'  => [
				'class'   => RoleFilter::className(),
				'name'    => "Thống kê",
				'actions' => [
					'index'            => "Danh sách",
					'create'           => "Thêm",
					'update'           => "Sửa",
					'delete'           => "Xóa",
				],
			],
		];
	}

	/**
	 *
	 */
	public function actionIndex() {
		/*if (Yii::$app->user->identity->getRoleId()!= 1) {
			$event_in   = array();
			$eventUsers = EventUser::find()->where(['user_id' => Yii::$app->user->id]);
			foreach ($eventUsers as $eventUser) {
				$event_in[] = $eventUser->event_id;
			}
			//$models = Event::model()->findAllByPk($event_in);
			$models = Event::find()->where($event_in)->all();
		} else {
			$models = Event::find()->where(["status"=>1])->all();
		}*/
		$model = new Event();
		return $this->render('index', ['model' => $model]);
	}

	public function actionView($id) {
		$cookies = Yii::$app->response->cookies;
		unset($cookies['start_date']);
		unset($cookies['end_date']);
		$order1 = new Order();
		//$order->attributes()->;
		$orders = Order::find();
		if (isset($_GET['Order'])) {
			$order1->attributes = $_GET['Order'];
			$order1->branch_id  = $_GET['Order']['branch_id'];
			if (isset($_GET['Order']['branch_id']) && $_GET['Order']['branch_id'] != '') {
				/**@var User[] $users */
				$users   = User::find()->where(['branch_id' => $_GET['Order']['branch_id']])->all();
				$user_id = array();
				foreach ($users as $user) {
					$user_id[] = $user->id;
				}
				$orders->andWhere([
					'user_id',
					$user_id,
				]);
			}

		}
		if (isset($_POST['Order'])) {
			$cookies->add( new Cookie(['name'=>'start_date','value'=> $_POST['Order']['start_date']]));
			$cookies->add(new Cookie(['name'=>'end_date', 'value'=> $_POST['Order']['end_date']]));
			$order1->start_date                         = Yii::$app->response->cookies['start_date']->value;
			$order1->end_date                           = Yii::$app->response->cookies['end_date']->value;

			$start_date        = date("Y-m-d H:i:s", strtotime($_POST['Order']['start_date'] . ' 00:00:00'));
			$end_date          = date("Y-m-d H:i:s", strtotime($_POST['Order']['end_date'] . ' 23:59:59'));
			$orders->where("booked_date >= '" . $start_date . "' AND booked_date <= '" . $end_date . "'");
		} else {
			$order1->start_date = date('Y-m-d', time() - 604800);
			$order1->end_date   = date('Y-m-d', time());
			$orders->where(['>=','booked_date', date('Y-m-d H:i:s', time() - 604800)])->andWhere([ '<= ','booked_date' , date('Y-m-d H:i:s', time())]);
		}

		/**@var Order[] $models */
		$models    = $orders->andwhere([
			'event_id' => $id,
		])->andWhere(['<>','status',0])->orderBy(['booked_date' => SORT_DESC,'id'=>SORT_DESC])->all();

		$model     = array();
		$statistic = array(
			'seat'     => 0,
			'discount' => 0,
			'total'    => 0,
		);
		foreach ($models as $modelOrder) {
			if (!isset($model[$modelOrder->user_id])) {
				$model[$modelOrder->user_id] = $modelOrder;
				if ($modelOrder->discount_type == 0) {
					$model[$modelOrder->user_id]->discount = ($modelOrder->discount * $modelOrder->subTotal / 100);
				}
				$model[$modelOrder->user_id]->seat_count = count($modelOrder->orderSeats);
				$model[$modelOrder->user_id]->oweTotal   += $modelOrder->remain;
			} else {
				$model[$modelOrder->user_id]->grandTotal += $modelOrder->grandTotal;
				if ($modelOrder->discount_type == 0) {
					$model[$modelOrder->user_id]->discount += ($modelOrder->discount * $modelOrder->subTotal / 100);
				} else {
					$model[$modelOrder->user_id]->discount += $modelOrder->discount;
				}
				$model[$modelOrder->user_id]->seat_count += count($modelOrder->orderSeats);
				$model[$modelOrder->user_id]->oweTotal   += $modelOrder->remain;
			}
			$statistic['seat'] += count($modelOrder->orderSeats);
			$statistic['total'] += $modelOrder->grandTotal;
		}
		foreach ($model as $value) {
			$statistic['discount'] += $value->discount;
		}
		/**@var Price[] $prices */
		$prices   = Price::find()->where([
			'<>',
			'price',
			0,
		])->andWhere(['event_id' => $id])->all();
		$allSeats = Json::decode(file_get_contents(Yii::getAlias('@app') . '/svg/' . $id . '.json'));
		$seats    = array();
		foreach ($prices as $price) {
			$array = array(
				'price'   => number_format($price->price),
				'color'   => $price->color,
				'total'   => 0,
				'sold'    => 0,
				'instock' => 0,
			);
			foreach ($allSeats as $allSeat) {
				if ($allSeat['class'] == 'fil' . $price->class) {
					$array['total'] += 1;
				}
			}
			$array['instock']  = $array['total'] - $array['sold'];
			$seats[$price->id] = $array;
		}
		/**@var Order[] $orders */
		$orders = Order::find()->where([
			'event_id' => $id,
		])->andWhere(['<>','status',0])->all();
		foreach ($orders as $order) {
			foreach ($order->orderSeats as $orderSeat) {
				if ($orderSeat->status == 1) {
					$seats[$orderSeat->price_id]['sold'] += 1;
					$seats[$orderSeat->price_id]['instock'] -= 1;
				}
			}
		}

		$provider = new ArrayDataProvider([
			'allModels'  => $model,
			'pagination' => [
				'pageSize' => 10,
			],
		]);

		return $this->render('view', array(
			'seats'     => $seats,
			'model'     => $provider,
			'order'     => $order1,
			'statistic' => $statistic,
		));
	}
}