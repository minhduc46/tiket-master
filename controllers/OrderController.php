<?php
namespace app\controllers;

use app\components\Controller;
use app\components\Pdf;
use app\models\Event;
use app\models\EventUser;
use app\models\Order;
use app\models\OrderSeat;
use app\models\search\OrderSearch;
use DateTime;
use HttpException;
use navatech\role\filters\RoleFilter;

use PHPExcel_IOFactory;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

class OrderController extends Controller {

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
				'name'    => "Đơn hàng",
				'actions' => [
					'index'  => "Danh sách",
					'create' => "Thêm",
					'update' => "Sửa",
					'delete' => "Xóa",
					'view'   => "Xem",
                    'excel'  =>"Xuất Excel"
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
	 * @return string
	 */
	public function actionCreate() {
		Yii::$app->response->format = 'json';
		$model                      = new Order();
		if(isset($_POST['Order'])) {
			$event = Event::findOne($_POST['Order']['event_id']);
			if($event->validateTime() == 0) {
				$db = Yii::$app->db->beginTransaction();
				try {
					$model->attributes = $_POST['Order'];
					$model->total      = str_replace(',', '', $_POST['Order']['total']);
					$model->remain     = str_replace(',', '', $_POST['Order']['remain']);
					$model->status     = $_POST['Order']['status'];
					$model->note       = $_POST['Order']['note'];
					if($model->discount_type == "") {
						$model->discount_type = 0;
					}
					if($model->remain == "") {
						$model->remain = 0;
					}
					if(!isset($_POST['Order']['agency_id']) || empty($_POST['Order']['agency_id'])) {
						$model->agency_id = null;
					}
					$model->discount = str_replace(",", "", $_POST['Order']['discount']);
					if($_POST['Order']['number'] == '') {
						if(!$model->checkOrder($_POST['Order']['event_id'], $_POST['Order']['seat'])) {
							echo Json::encode(array('code' => '1'));
							Yii::$app->end();
						}
						$model->number       = $model::getOrderNumber($_POST['Order']['event_id']);
						$model->booked_date  = date("Y-m-d H:i:s");
						$model->adjourn_date = $model->booked_date;
					} else {
						/**@var Order $order */
						$order = Order::find()->where([
							'event_id' => $_POST['Order']['event_id'],
							'number'   => $_POST['Order']['number'],
						])->one();
						if($order) {
							$model->booked_date  = $order->booked_date;
							$model->adjourn_date = $model->booked_date;
						} else {
							$model->booked_date  = date("Y-m-d H:i:s");
							$model->adjourn_date = $model->booked_date;
						}
					}
					if($model->save()) {
						$result = array();
						foreach($_POST['Order']['seat'] as $seat) {
							$order_seat           = new OrderSeat();
							$order_seat->order_id = $model->id;
							$order_seat->price_id = $seat['price-id'];
							$seatInfo             = explode('-', $seat['id']);
							$order_seat->row      = $seatInfo[0];
							$order_seat->number   = $seatInfo[1];
							$order_seat->floor    = $seatInfo[2];
							if($order_seat->save()) {
								$result[] = $order_seat->attributes;
							}
						}
						$db->commit();
						return Json::encode(array(
							'code'     => '0',
							'number'   => str_pad($model->number, 7, "0", STR_PAD_LEFT),
							'event_id' => $model->event_id,
							'result'   => $result,
						));
					} else {
						return Json::encode(ArrayHelper::merge([
							'code'  => '1',
							'error' => 1,
						], $model->getErrors()));
					}
				} catch(\Exception $e) {
					$db->rollBack();
					return Json::encode(ArrayHelper::merge([
						'code'  => '1',
						'error' => 2,
					], $model->getErrors()));
				}
			} elseif($event->validateTime() == 1) {
				return Json::encode(array(
					'code'    => '2',
					'message' => 'Chương trình bán vé chưa bắt đầu!',
				));
			}
		}
		echo Json::encode(array(
			'code'    => '2',
			'message' => 'Chương trình bán vé đã kết thúc!',
		));
	}

	/**
	 * @param $number
	 * @param $event_id
	 *
	 * @return string
	 * @throws HttpException
	 */
	public function actionView($number, $event_id) {
		$number = (int) $number;
		$model  = Order::find()->where([
			'number'   => $number,
			'event_id' => $event_id,
		])->andWhere([
			'<>',
			'status',
			0,
		])->one();
		if($this->user->role_id != 1) {
			$event_id = ArrayHelper::map(EventUser::find()->where(['user_id' => Yii::$app->user->id])->all(), 'id', 'event_id');
			if(!in_array($model->event_id, $event_id)) {
				throw new HttpException(404, 'The requested page does not exist.');
			}
		}
		if($model->isEdited()) {
			$diffs = Order::find()->where([
				'number'   => $model->number,
				'event_id' => $model->event_id,
			])->orderBy(['updated_date' => SORT_DESC])->all();
		} else {
			$diffs = null;
		}
		return $this->render('view', [
			'model' => $model,
			'diffs' => $diffs,
		]);
	}

	/**
	 * @param $number
	 * @param $event_id
	 *
	 * @return string
	 * @throws HttpException
	 */
	public function actionPrint($number, $event_id) {
		/**@var Order $model */
		$model = Order::find()->where([
			'number'   => $number,
			'event_id' => $event_id,
		])->andWhere([
			'<>',
			'status',
			0,
		])->orderBy(['status' => SORT_DESC])->one();
		if($this->user->role_id != 1) {
			$events_id = ArrayHelper::map(EventUser::find()->where(['user_id' => Yii::$app->user->id])->all(), 'id', 'event_id');
			if(!in_array($model->event_id, $events_id)) {
				throw new HttpException(404, 'The requested page does not exist.');
			}
		}
		if($model->isEdited()) {
			$diffs = Order::find()->where([
				"number"   => $model->number,
				"event_id" => $model->event_id,
			])->andWhere([
				'<>',
				'status',
				0,
			])->orderBy(['updated_date' => SORT_DESC])->limit(3)->all();
		} else {
			$diffs = null;
		}
		$this->layout = false;
		$content      = $this->render('print', [
			'model' => $model,
			'diffs' => $diffs,
		]);
		$footer       = '<div class="footer" style="position: absolute; bottom: 0; left: 0">
		<p style="font-size: 10px">Liên 1: Lưu</p>
		<p style="font-size: 10px">Liên 2: Shiper</p>
		<p style="font-size: 10px">Liên 3: Khách hàng</p>
		</div>';
		$pdf          = new Pdf([
			'mode'         => Pdf::MODE_ASIAN,
			'format'       => Pdf::FORMAT_A5,
			'orientation'  => Pdf::ORIENT_PORTRAIT,
			'destination'  => Pdf::DEST_BROWSER,
			'content'      => $content,
			'marginTop'    => 2,
			'marginLeft'   => 2,
			'marginRight'  => 2,
			'marginBottom' => 2,
			'cssFile'      => '@app/web/css/print.css',
			'cssInline'    => '.kv-heading-1{font-size:18px}',
			'options'      => ['title' => 'Báo cáo'],
			'methods'      => [
				'SetFooter' => $footer,
			],
		]);
		$pdf->render();
		return $this->renderPartial('print', [
			'model' => $model,
			'diffs' => $diffs,
		]);
	}

	/**
	 * @return string
	 */
	public function actionIndex() {
	    $order=new Order();
		$searchModel  = new OrderSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $event_id="";
		if(isset(Yii::$app->request->queryParams['OrderSearch']['event_id']))
        {
            $event_id=Yii::$app->request->queryParams['OrderSearch']['event_id'];
        };
        $filterDiscount= ArrayHelper::map($order->getDiscountFilter(), 'id', 'value');
		return $this->render('index', [
			'searchModel'  => $searchModel,
			'dataProvider' => $dataProvider,
            'a1'=>$filterDiscount,
            'event_id'=>$event_id
		]);

	}

	/**
	 * Delete order
	 */
	public function actionDelete() {
		if(isset($_POST['number']) && isset($_POST['event_id'])) {
			/**@var Order[] $models */
			$models = Order::find()->where([
				'number'   => $_POST['number'],
				'event_id' => $_POST['event_id'],
			])->all();
			$return = array();
			foreach($models as $model) {
				if($model->status != 0) {
					$orderSeats = OrderSeat::find()->where(['order_id' => $model->id])->all();
					foreach($orderSeats as $orderSeat) {
						$return[] = $orderSeat->attributes;
					}
				}
				$model->delete();
			}
			echo Json::encode(array(
				'code'   => 0,
				'return' => $return,
			));
			Yii::$app->end();
		}
		echo Json::encode(array('code' => 1));
		Yii::$app->end();
	}

	/**
	 * @param $id
	 *
	 * @return string
	 */
	public function actionChairTimeOut($id) {
		$event = Event::findOne($id);
		$date  = new DateTime();
		date_add($date, date_interval_create_from_date_string('3 days'));
		$query = Order::find()->where([
			'<',
			'updated_date',
			date_format($date, 'Y-m-d'),
		])->andWhere([
			'status'       => 1,
			'event_id'     => $id,
			'adjourn_date' => '0000-00-00',
		]);
		// add conditions that should always apply here
		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);
		$query1       = Order::find()->where([
			'<',
			'adjourn_date',
			date_format($date, 'Y-m-d'),
		])->andWhere([
			'<>',
			'adjourn_date',
			'0000-00-00',
		])->andWhere([
			'status'   => 1,
			'event_id' => $event->id,
		]);
		// add conditions that should always apply here
		$dataProvider1 = new ActiveDataProvider([
			'query' => $query1,
		]);
		return $this->render('chair-time-out', [
			'dataProvider'  => $dataProvider,
			'dataProvider1' => $dataProvider1,
			'event'         => $event,
			'type'          => 1,
		]);
	}

	/**
	 * @param $id
	 *
	 * @return string
	 */
	public function actionOwe($id) {
		$event = Event::findOne($id);
		$date  = new DateTime();
		date_add($date, date_interval_create_from_date_string('3 days'));
		$query = Order::find()->where([
			'>',
			'remain',
			0,
		])->andWhere([
			'event_id' => $id,
		])->andWhere([
			'<>',
			'status',
			0,
		]);
		// add conditions that should always apply here
		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);
		return $this->render('chair-time-out', [
			'dataProvider' => $dataProvider,
			'event'        => $event,
			'type'         => 2,
		]);
	}

	public function actionXk($id) {
		$event = Event::findOne($id);
		$date  = new DateTime();
		date_add($date, date_interval_create_from_date_string('3 days'));
		$query = Order::find()->andWhere([
			'event_id' => $id,
			'status'   => Order::STATUS_CHUAXUAT,
		]);
		// add conditions that should always apply here
		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);
		return $this->render('chair-time-out', [
			'dataProvider' => $dataProvider,
			'event'        => $event,
			'type'         => 3,
		]);
	}

	/**
	 * @param $id
	 *
	 * @return string|\yii\web\Response
	 */
	public function actionGh($id) {
		$model = Order::findOne($id);
		$event = Event::findOne($model->event_id);
		if($model->adjourn_date == '0000-00-00') {
			$model->adjourn_date = date("Y-m-d");
		}
		if($model->load(Yii::$app->request->post())) {
			if($model->status = Order::STATUS_DAHUY) {
				$model->delete();
			}
			if($model->save()) {
				return $this->redirect([
					'chair-time-out',
					'id' => $event->id,
				]);
			}
		} else {
			return $this->render('edit-chair', [
				'model' => $model,
				'type'  => 1,
			]);
		}
	}

	public function actionXn($id) {
		$model = Order::findOne($id);
		$event = Event::findOne($model->event_id);
		if($model->load(Yii::$app->request->post())) {
			$model->remain = $_POST['Order']['remain'];
			if($model->save()) {
				return $this->redirect([
					'owe',
					'id' => $event->id,
				]);
			}
		} else {
			return $this->render('edit-chair', [
				'model' => $model,
				'type'  => 2,
			]);
		}
	}

	/**
	 * @param $id
	 *
	 * @return \yii\web\Response
	 */
	public function actionDeleteOrder($number, $event_id, $type) {
		Order::deleteAll([
			'event_id' => $event_id,
			'number'   => $number,
		]);
		if($type == 1) {
			return $this->redirect([
				'chair-time-out',
				'id' => $event_id,
			]);
		} elseif($type == 2) {
			return $this->redirect([
				'xk',
				'id' => $event_id,
			]);
		} elseif($type == 3) {
			return $this->redirect([
				'owe',
				'id' => $event_id,
			]);
		}
	}

	public function actionExcel($event_id)
    {
        if($event_id!=null)
        $models = Order::find()->where(['event_id'=>$event_id])->andWhere(['<>','status',0])->all();
        else
            $models = Order::find()->Where(['<>','status',0])->all();
        $phpExcelPath = Yii::getAlias('@app/extensions/phpexcel/Classes');
        spl_autoload_unregister(array(
            'YiiBase',
            'autoload',
        ));
        include($phpExcelPath . DIRECTORY_SEPARATOR . 'PHPExcel.php');
        $objPHPExcel = PHPExcel_IOFactory::load($phpExcelPath . DIRECTORY_SEPARATOR . 'template.xls');

        $objPHPExcel->getProperties()->setCreator("VNSHOW")->setLastModifiedBy("VNSHOW")->setTitle("VNSHOW")->setSubject("VNSHOW")->setDescription("VNSHOW")->setKeywords("VNSHOW")->setCategory("VNSHOW");
        $row = 3;
        $objPHPExcel->setActiveSheetIndex(0);
        /**@var Order $model */
        foreach ($models as $model) {
            if (isset($model->event)) {
                $row_temp = $row;
                $objPHPExcel->getActiveSheet()->setCellValue('A' . $row, $model->event->name)->setCellValue('B' . $row, ' ' . str_pad($model->number, 7, "0", STR_PAD_LEFT))->setCellValue('C' . $row, substr($model->booked_date, - 8))->setCellValue('D' . $row, substr($model->booked_date, 0, 10))->setCellValue('E' . $row, $model->customer_name)->setCellValue('F' . $row, ' ' . $model->customer_phone)->setCellValue('G' . $row, $model->customer_address)->setCellValue('H' . $row, isset($model->branch) ? $model->getTextBrain($model->getTextBrain($model->user_id)) : '')->setCellValue('I' . $row, $model->user->username)->setCellValue('N' . $row, count($model->orderSeats))->setCellValue('O' . $row, $model->subTotal)->setCellValue('P' . $row, $model->discount . ($model->discount_type == 0 ? '%' : ''))->setCellValue('Q' . $row, $model->grandTotal)->setCellValue('R' . $row, $model->isEdited() ? 'Có' : 'Không')->setCellValue('S' . $row, $model->note);
                foreach ($model->getExcelDescription() as $md) {
                    $objPHPExcel->getActiveSheet()->setCellValue('J' . $row_temp, $md['price'])->setCellValue('K' . $row_temp, $md['quantity'])->setCellValue('L' . $row_temp, $md['seat'])->setCellValue('M' . $row_temp, $md['total']);
                    $row_temp ++;
                }
                if (($row_temp - 1) > $row) {
                    $objPHPExcel->getActiveSheet()->mergeCells('A' . $row . ':A' . ($row_temp - 1))->mergeCells('B' . $row . ':B' . ($row_temp - 1))->mergeCells('C' . $row . ':C' . ($row_temp - 1))->mergeCells('D' . $row . ':D' . ($row_temp - 1))->mergeCells('E' . $row . ':E' . ($row_temp - 1))->mergeCells('F' . $row . ':F' . ($row_temp - 1))->mergeCells('G' . $row . ':G' . ($row_temp - 1))->mergeCells('H' . $row . ':H' . ($row_temp - 1))->mergeCells('I' . $row . ':I' . ($row_temp - 1))->mergeCells('N' . $row . ':N' . ($row_temp - 1))->mergeCells('O' . $row . ':O' . ($row_temp - 1))->mergeCells('P' . $row . ':P' . ($row_temp - 1))->mergeCells('Q' . $row . ':Q' . ($row_temp - 1))->mergeCells('R' . $row . ':R' . ($row_temp - 1))->mergeCells('S' . $row . ':S' . ($row_temp - 1));
                }
                $row = $row_temp;
            }
        }
        $objPHPExcel->getActiveSheet()->getStyle('A3:R' . $row)->getAlignment()->setWrapText(true);
        $objPHPExcel->getActiveSheet()->setTitle('Báo cáo chi tiết');
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="report_' . date('d-m-Y', time()) . '.xls"');
        header('Cache-Control: max-age=0');
        spl_autoload_unregister(array(
            'YiiBase',
            'autoload',
        ));
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        Yii::$app->end();
        spl_autoload_register(array(
            'YiiBase',
            'autoload',
        ));
    }
}
