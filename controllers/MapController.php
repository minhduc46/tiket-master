<?php
namespace app\controllers;

use app\components\Controller;
use app\components\Svg;
use app\models\Branch;
use app\models\Event;
use app\models\EventUser;
use app\models\Order;
use app\models\OrderSeat;
use app\models\Price;
use app\models\User;
use navatech\role\filters\RoleFilter;
use Yii;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\HttpException;

class MapController extends Controller {

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
				'name'    => "Bản đồ",
				'actions' => [
					'view' => "Xem",
					'statistic'=>'Thống kê',
					'seat-statistic'=>'Thống kê vé',
					'map-order' => 'Đặt vé'
				],
			],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function actions() {
		return [
			'error'   => [
				'class' => 'yii\web\ErrorAction',
			],
			'captcha' => [
				'class'           => 'yii\captcha\CaptchaAction',
				'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
			],
		];
	}

	/**
	 * Displays homepage.
	 *
	 * @param int $id
	 *
	 * @return string
	 * @throws HttpException
	 */
	public function actionView($id) {
		$user_id = Yii::$app->user->id;
		$event   = Event::findOne($id);
		if ($event) {
			$order          = new Order();
			$order->user_id = $user_id;
			$eventUser      = EventUser::find()->where([
				'user_id'  => $user_id,
				'event_id' => $id,
			])->all();
			//rolle
			if ($user_id && ($this->user->role_id == 1 || ($event && $eventUser))) {
				$orders      = Order::find()->where([
					'event_id' => $id,
				])->andWhere([
					'<>',
					'status',
					0,
				])->all();
				$bookedSeats = array();
				foreach ($orders as $orderModel) {
					$orderSeats = OrderSeat::find()->where(['order_id' => $orderModel->id])->all();
					foreach ($orderSeats as $orderSeat) {
						$bookedSeats[] = array_diff_key($orderSeat->attributes, array_flip(array(
							'order_seat_id',
							'order_id',
							'price_id',
							'status',
							'id',
						)));
					}
				}
				$seatData = '';
				$prices   = Price::find()->where(['event_id' => $event->id])->andWhere([
					'<>',
					'price',
					0,
				])->all();
				if (file_exists(Yii::getAlias('@app') . '/svg/' . $id . '.json')) {
					$seatJson = Json::decode(file_get_contents(Yii::getAlias('@app') . '/svg/' . $id . '.json'), true);
					foreach ($seatJson as $key => $seat) {
						if ($id == 5) {
							$seatData .= Svg::drawSeat($seat, 8, $bookedSeats, $prices);
						} else if ($id == 6 || $id == 8 || $id == 10) {
							$seatData .= Svg::drawSeat($seat, 12, $bookedSeats, $prices);
						} else {
							$seatData .= Svg::drawSeat($seat, 6, $bookedSeats, $prices);
						}
					}
					return $this->render('view', [
						'prices'      => $prices,
						'order'       => $order,
						'event'       => $event,
						'bookedSeats' => $bookedSeats,
						'seatData'    => $seatData,
					]);
				} else {
					throw new HttpException(404, 'Chương trình này hiện tại chưa có bản đồ khu vực giá! Vui lòng liên hệ Navatech để cập nhật.');
				}
			} else {
				throw new HttpException(404, 'The requested page does not exist.');
			}
		} else {
			throw new HttpException(404, 'The requested page does not exist.');
		}
	}

	/**
	 * Kiểm tra chỗ ngồi vừa check
	 *
	 * @return string
	 */
	public function actionCheckSeat() {
		$user_id = Yii::$app->user->id;
		if (isset($_POST['OrderSeat'])) {
			$post = $_POST['OrderSeat'];
			/**@var OrderSeat $model */
			$model = OrderSeat::find()->where([
				'price_id' => $post["price_id"],
				'row'      => $post['row'],
				'number'   => $post['number'],
				'floor'    => $post['floor'],
			])->andWhere([
				'<>',
				'status',
				0,
			])->one();
			$user  = User::findOne($user_id);
			if ($model) {
				$order    = Order::findOne($model->order_id);
				$response = array(
					'html'  => '',
					'total' => 0,
				);
				if ($order) {
					/**@var OrderSeat[] $orderSeats */
					$orderSeats = OrderSeat::find()->where(['order_id' => $order->id])->all();
					foreach ($orderSeats as $orderSeat) {
						$price = Price::findOne($orderSeat->price_id);
						$response['html'] .= '<div class="col-xs-12 order-item" data-price-id="' . $price->id . '" data-price="' . $price->price . '" data-id="' . $orderSeat->row . '-' . $orderSeat->number . '-' . $orderSeat->floor . '">
						<div class="col-xs-1 trash">
							<div>
								<i class=" glyphicon glyphicon-trash"></i>
							</div>
						</div>
						<div class="col-xs-3">' . $orderSeat->row . '-' . $orderSeat->number . '</div>
						<div class="col-xs-3">' . $orderSeat->floor . '</div>
						<div class="col-xs-4">' . number_format($price->price) . '</div>
					</div>';
						$response['total'] += $price->price;
					}
					$response['code']         = 1;
					$response['username']     = $order['user']->username;
					$response['branchname']   = $order['user']['branch']->name;
					$response['order_number'] = $order->number;
					$timestamp                = strtotime($order->updated_date);
					$response['updated_date'] = date('d-m-Y H:i:s', $timestamp);
					$response                 = ArrayHelper::merge($order->attributes, $response);
				}
				$response['remain'] = $order->remain;
				$response['status'] = $order->status;
				//$response['owner']  = $order->id;
				if ($this->user->role_id != 1 && !in_array($response['user_id'], array_keys($user->branch->getAllUser()))) {
					$response['owner'] = 0;
				} else {
					$response['owner'] = 1;
				}
				echo JSON::encode($response);
			} else {
				$price = Price::findOne($post['price_id']);
				if ($price) {
					$html = '<div class="col-xs-12 order-item" data-price-id="' . $price->id . '" data-price="' . $price->price . '" data-id="' . $post['row'] . '-' . $post['number'] . '-' . $post['floor'] . '">
						<div class="col-xs-1 trash">
							<div>
								<i class="red glyphicon glyphicon-trash" style="color: red;"></i>
							</div>
						</div>
						<div class="col-xs-3">' . $post['row'] . '-' . $post['number'] . '</div>
						<div class="col-xs-3">' . $post['floor'] . '</div>
						<div class="col-xs-4">' . number_format($price->price) . '</div>
					</div><br>';
					echo JSON::encode(array(
						'code'       => 1,
						'username'   => $user->username,
						'branchname' => $user['branch']->name,
						'price'      => $price->price,
						'html'       => $html,
						'owner'      => 1,
					));
				} else {
					echo JSON::encode(array('code' => 0));
				}
			}
		}
	}

	public function actionSeatStatistic($id) {
		//"price <> 0 AND event_id = " . $id . " ORDER BY price_id ASC"
		/**@var Price[] $prices */
		$prices        = Price::find()->where([
			'<>',
			'price',
			0,
		])->andWhere(['event_id' => $id])->orderBy(['id' => SORT_ASC])->all();
		$allSeats      = JSON::decode(file_get_contents(Yii::getAlias('@app') . '/svg/' . $id . '.json'));
		$seatStatistic = array();
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
			$array['instock']          = $array['total'] - $array['sold'];
			$seatStatistic[$price->id] = $array;
		}
		//"status = 1 AND event_id = " . $id
		/**@var Order[] $orders */
		$orders = Order::find()->where([
			'event_id' => $id,
		])->andWhere(['<>','status',0])->all();
		//"status = 1 AND order_id = " . $order->order_id
		foreach ($orders as $order) {
			/**@var OrderSeat[] $orderSeats */
			$orderSeats = OrderSeat::find()->where([
				'status'   => 1,
				'order_id' => $order->id,
			])->all();
			foreach ($orderSeats as $orderSeat) {
				$seatStatistic[$orderSeat->price_id]['sold'] += 1;
				$seatStatistic[$orderSeat->price_id]['instock'] -= 1;
			}
		}
		return $this->renderPartial('seat-statistic', array(
			'seatStatistic' => $seatStatistic,
		));
	}

	public function actionCheckBranch() {
	}

	public function actionStatistic($id) {
		$all   = array();
		$today = array();
		/**@var Branch[] $branches */
		$branches = Branch::find()->all();
		$seats    = JSON::decode(file_get_contents(Yii::getAlias('@app') . '/svg/' . $id . '.json'), true);
		foreach ($branches as $key => $branch) {
			$all[$key]   = array(
				'name'     => $branch->name,
				'revenue'  => 0,
				'discount' => 0,
				'ticket'   => 0,
				'remain1'   => 0,
			);
			$today[$key] = array(
				'name'     => $branch->name,
				'revenue'  => 0,
				'discount' => 0,
				'ticket'   => 0,
				'remain1'   => 0,
			);
			$user_id     = array();
			foreach ($branch->users as $user) {
					$user_id[] = $user->id;
			}

			/*$criteria            = new Query();
			$criteria-> = "status = 1 AND event_id = " . $id;
			$criteria->addInCondition('user_id', $user_id);*/
			/**@var Order[] $orders */
			$orders = Order::find()->where([
				'event_id' => $id,
				'user_id'  => $user_id,
			])->andWhere(['<>','status',0])->all();

			foreach ($orders as $order) {
				$all[$key]['revenue'] += $order->grandTotal;
				if ($order->discount_type == 0) {
					$all[$key]['discount'] += ($order->discount * $order->subTotal / 100);
				} else {
					$all[$key]['discount'] += $order->discount;
				}
				$all[$key]['remain1']+=$order->remain;
				$all[$key]['ticket'] += OrderSeat::find()->where(['order_id' => $order->id])->count();
				$booked_date = explode(" ", $order->booked_date);

				if ($booked_date[0] == date('Y-m-d')) {
					$today[$key]['revenue'] += $order->grandTotal;
					if ($order->discount_type == 0) {
						$today[$key]['discount'] += ($order->discount * $order->subTotal / 100);
					} else {
						$today[$key]['discount'] += $order->discount;
					}
					$today[$key]['remain1'] += $order->remain;
					$today[$key]['ticket'] += OrderSeat::find()->where(['order_id' => $order->id])->count();
				}
			}

			return $this->renderPartial('statistic', array(
				'all'   => $all,
				'today' => $today,
				'count' => count($seats),
			));
		}
	}
}
