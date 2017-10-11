<?php
namespace app\models;

use app\components\ActiveRecord;
use DateTime;
use yii\data\ArrayDataProvider;

/**
 * This is the model class for table "order".
 *
 * @property integer     $id
 * @property integer     $number
 * @property integer     $event_id
 * @property integer     $user_id
 * @property integer     $agency_id
 * @property string      $customer_name
 * @property string      $customer_phone
 * @property string      $customer_address
 * @property double      $discount
 * @property integer     $discount_type
 * @property integer     $total
 * @property integer     $remain
 * @property string      $note
 * @property integer     $status
 * @property string      $updated_date
 * @property string      $booked_date
 * @property string      $adjourn_date
 *
 * @property Event       $event
 * @property User        $user
 * @property Agency      $agency
 * @property OrderSeat[] $orderSeats
 */
class Order extends ActiveRecord {

	public $from_date;

	public $maxId;

	public $to_date;

	public $start_date;

	public $end_date;

	public $subTotal;

	public $grandTotal;

	public $oweTotal;

	public $description;

	public $edited;

	public $seat_count;

	public $event_in = null;

	public $branch_id;

	public $branch;

	/**
	 * @inheritdoc
	 */
	const STATUS_DAHUY     = 0;

	const STATUS_GIUGHE    = 1;

	const STATUS_CHUAXUAT  = 2;

	const STATUS_DAXUAT    = 3;

	const STATUS_HOANTHANH = 4;

	public $Chua_Xuat = 2;

	public static function tableName() {
		return 'order';
	}

	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [
			[
				[
					'event_id',
					'user_id',
					'customer_name',
					'customer_phone',
					'customer_address',
				],
				'required',
			],
			[
				[
					'number',
					'event_id',
					'user_id',
					'discount_type',
					'status',
				],
				'integer',
			],
			[
				['discount'],
				'number',
			],
			[
				['note'],
				'string',
			],
			[
				[
					'agency_id',
					'updated_date',
					'updated_date',
					'booked_date',
					'adjourn_date',
					'edited',
				],
				'safe',
			],
			[
				[
					'customer_name',
					'customer_phone',
					'customer_address',
				],
				'string',
				'max' => 255,
			],
			[
				['order_id, order_number, event_id, user_id, customer_name, customer_phone, customer_address, booked_date, discount, status, note, edited, discount_type'],
				'safe',
				'on' => 'search',
			],
			[
				['event_id'],
				'exist',
				'skipOnError'     => true,
				'targetClass'     => Event::className(),
				'targetAttribute' => ['event_id' => 'id'],
			],
			[
				['user_id'],
				'exist',
				'skipOnError'     => true,
				'targetClass'     => User::className(),
				'targetAttribute' => ['user_id' => 'id'],
			],
			[
				['agency_id'],
				'exist',
				'skipOnError'     => true,
				'targetClass'     => Agency::className(),
				'targetAttribute' => ['agency_id' => 'id'],
			],
		];
	}

	public static function getArrStatus() {
		return [
			'Đã hủy',
			'Giữ ghế',
			'Chưa xuất',
			'Đã xuất',
			'Đã hoàn thành',
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {
		return [
			'id'               => 'ID',
			'number'           => 'Hóa đơn',
			'event_id'         => 'Tên sự kiện',
			'user_id'          => 'Người bán',
			'agency_id'        => 'Tên đại lý',
			'customer_name'    => 'Tên khách hàng',
			'customer_phone'   => 'Điện thoại ',
			'customer_address' => 'Địa chỉ',
			'discount'         => 'Chiết khấu',
			'discount_type'    => 'Loại chiết khấu',
			'total'            => 'Tổng',
			'remain'           => 'Nợ',
			'note'             => 'Ghi chú',
			'status'           => 'Trạng thái',
			'updated_date'     => 'Ngày cập nhập',
			'booked_date'      => 'Ngày đặt vé',
			'adjourn_date'     => 'Gia hạn',
			'edited'           => 'Sửa chữa?',
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getEvent() {
		return $this->hasOne(Event::className(), ['id' => 'event_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getUser() {
		return $this->hasOne(User::className(), ['id' => 'user_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getAgency() {
		return $this->hasOne(Agency::className(), ['id' => 'agency_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getOrderSeats() {
		return $this->hasMany(OrderSeat::className(), ['order_id' => 'id'])->where(['status' => 1]);
	}

	/**
	 * @param $event_id
	 * @param $seats
	 *
	 * @return bool
	 */
	public function checkOrder($event_id, $seats) {
		foreach ($seats as $seat) {
			$seatInfo = explode('-', $seat['id']);
			/**@var OrderSeat[] $models */
			$models = OrderSeat::find()->distinct(true)->select('order_id')->where([
				'floor'  => $seatInfo[2],
				'row'    => $seatInfo[0],
				'number' => $seatInfo[1],
				'status' => 1,
			])->all();
			foreach ($models as $model) {
				return !Order::find()->where([
					'id'       => $model->order_id,
					'status'   => 1,
					'event_id' => $event_id,
				])->exists();
			}
		}
		return true;
	}

	/**
	 * @param null $event_id
	 *
	 * @return mixed
	 */
	public static function getOrderNumber($event_id = null) {
		if ($event_id != null) {
			return Order::find()->where(['event_id' => $event_id])->max('number') + 1;
		} else {
			return Order::find()->max('number') + 1;
		}
	}

	/**
	 * @return bool
	 */
	public function isEdited() {
		return Order::find()->where([
				'number'   => $this->number,
				'event_id' => $this->event_id,
			])->count() > 1;

	}

	public function isEdited2($number, $event_id) {
		return Order::find()->where([
				'number'   => $number,
				'event_id' => $event_id,
			])->count() > 1;
	}

	/**
	 * {@inheritDoc}
	 */
	public function beforeSave($insert) {
		if($this->isNewRecord) {
			/**@var Order[] $models */
			$models = Order::find()->where([
				'event_id' => $this->event_id,
				'number'   => $this->number,
			])->andWhere([
				'<>',
				'status',
				0,
			])->all();
			foreach($models as $model) {
				$model->updateAttributes(['status' => 0]);
				OrderSeat::updateAll(['status' => 0], ['order_id' => $model->id]);
			}
		}
		$this->updated_date = date('Y-m-d H:i:s');
		return parent::beforeSave($insert);
	}

	/**
	 * @param $id_event
	 *
	 * @return string
	 */
	public function getTextEvent($id_event) {
		$event = Event::findOne($id_event);
		if($event == null) {
			return "Không tìm thấy";
		} else {
			return $event->name;
		}
	}

	/**
	 * @param $id_user
	 *
	 * @return string
	 */
	public function getTextBrain($id_user) {
		$user = User::findOne($id_user);
		if ($user == null) {
			return "Không tìm thấy";
		} else {
			$brain = Branch::findOne($user->branch_id);
			if ($brain == null) {
				return "Không tìm thấy";
			} else {
				return $brain->name;
			}
		}
	}

	/**
	 * @param $id_user
	 *
	 * @return string
	 */
	public function getTextUser($id_user) {
		$user = User::findOne($id_user);
		if ($user == null) {
			return "Không tìm thấy";
		} else {
			return $user->username;
		}
	}

	/**
	 * @return string
	 */
	public function getDescription() {
		$orderSeats = $this->getOrderSeatArray();
		$html       = '';
		if ($orderSeats != null) {
			$html .= '<div class="description">';
			foreach ($orderSeats as $orderSeat) {
				$html .= "<div class='col-sm-12'>";
				$html .= "<span class='col-sm-2' style='text-align: right;'>" . number_format($orderSeat['price']) . "</span>";
				$html .= "<span class='col-sm-1' style='text-align: center;'>" . $orderSeat['count'] . "</span>";
				$html .= "<span class='col-sm-6'>" . $orderSeat['seats'] . "</span>";
				$html .= "<span class='col-sm-3' style='text-align: right;'>" . number_format($orderSeat['total']) . "</span>";
				$html .= "</div>";
			}
			$html .= '</div>';
		}
		return $html;
	}

	/**
	 * @return array
	 */
	public function getOrderSeatArray() {
		/**@var OrderSeat[] $orderSeats */
		$orderSeats = $this->orderSeats;
		$response   = array();
		/**@var Price[] $prices */
		$prices     = Price::find()->where(['event_id' => $this->event_id])->andWhere([
			'<>',
			'price',
			0,
		])->all();
		$priceArray = array();
		foreach ($prices as $price) {
			$priceArray[$price->id] = array(
				'price' => $price->price,
				'count' => 0,
			);
		}
		foreach ($orderSeats as $orderSeat) {
			$priceArray[$orderSeat->price_id]['count'] += 1;
		}
		foreach ($priceArray as $key => $elm) {
			if ($elm['count'] != 0) {
				$response[] = array(
					'price' => $elm['price'],
					'count' => $elm['count'],
					'seats' => $this->getSeatString($key),
					'total' => ($elm['count'] * $elm['price']),
				);
			}
		}
		return $response;
	}

	/**
	 * @param int  $price_id
	 * @param null $add
	 * @param null $del
	 *
	 * @return string
	 */
	public function getSeatString($price_id = 0, $add = null, $del = null) {
		if ($price_id != 0) {
			/**@var OrderSeat[] $orderSeats */
			$orderSeats = OrderSeat::find()->where([
				'order_id' => $this->id,
				'price_id' => $price_id,
			])->orderBy([
				'price_id' => SORT_ASC,
				'id'       => SORT_ASC,
			])->all();
		} else {
			/**@var OrderSeat[] $orderSeats */
			$orderSeats = OrderSeat::find()->where([
				'order_id' => $this->id,
			])->orderBy([
				'price_id' => SORT_ASC,
				'id'       => SORT_ASC,
			])->all();
		}
		$array = array();
		foreach ($orderSeats as $orderSeat) {
			$array[] = $orderSeat->row . '-' . $orderSeat->number;
		}
		if ($del != null) {
			foreach ($del as $seatDel) {
				$array[] = '<u class="red">' . $seatDel . '</u>';
			}
		}
		if ($add != null) {
			foreach ($add as $seatAdded) {
				unset($array[array_search($seatAdded, $array)]);
				$array[] = '<b class="green">' . $seatAdded . '</b>';
			}
		}
		return implode(", ", $array);
	}

	/**
	 * @return string
	 */
	public function getDiscountType() {
		return $this->discount_type == 0 ? '%' : '₫';
	}

	/**
	 * {@inheritDoc}
	 */
	public function afterFind() {
		parent::afterFind();
		/**@var OrderSeat[] $orderSeats */
		$this->oweTotal = Order::find()->where(['event_id' => $this->id])->sum('remain');
		$this->subTotal = $this->getOrderSeats()->alias('os')->leftJoin(['p' => 'price'], 'p.id = os.price_id')->sum('p.price');
		if ($this->discount_type == 0) {
			$this->grandTotal = $this->subTotal - ($this->discount * $this->subTotal / 100);
		} else {
			$this->grandTotal = $this->subTotal - $this->discount;
		}
		//		$user = User::findOne(\Yii::$app->user->id);
		/*	if($this->user->role_id != 1 && !in_array($this->user_id, ArrayHelper::map($user->branch->users, 'id', 'id'))) {
				$this->customer_address = str_pad('', strlen($this->customer_address), "*");
				$this->customer_phone   = str_pad('', strlen($this->customer_phone), "*");
			}*/
	}

	/**
	 * @param $event_id
	 * @param $date
	 * @param $return
	 *
	 * @return float|int
	 */
	public function getCountChart($event_id, $date, $return) {

		/**@var Order[] $orders**/
		$orders =Order::find()->where(['<>','status',0])->andWhere(['event_id'=>$event_id])->andWhere(['date(booked_date)'=>$date])->all();
		if ($return == 0) {
			$count_oder_seat = 0;
			foreach ($orders as $order) {
				$count_oder_seat += count($order->orderSeats);
			}
			return $count_oder_seat;
		} elseif ($return == 1) {
			$total = 0;
			foreach ($orders as $order) {
				$total += $order->grandTotal;
			}
			return $total;
		} elseif ($return == 2) {
			$total = 0;
			foreach ($orders as $order) {
				$total += $order->remain;
			}
			return $total;
		} else {
			$discount = 0;
			foreach ($orders as $order) {
				if ($order->discount_type == 0) {
					$discount += ($order->discount * $order->subTotal / 100);
				} else {
					$discount += $order->discount;
				}
			}
			return $discount;
		}
	}

	// Lấy tất cả sự kiện còn đang hoạt động
	private static function getEventActive() {
		$date_now = date("Y/m/d");
		return Event::find()->where(['status' => 1])->andWhere([
			'>=',
			'end_date',
			$date_now,
		])->all();
	}

	public static function getAllChairTimeOut($id) {
		$arr = [
			'id'     => null,
			'name'   => null,
			'number' => 0,
		];;
		$date  = new DateTime();
		$event = Event::findOne($id);
		date_sub($date, date_interval_create_from_date_string('3 days'));

		$number_chairn_time_out2 = Order::find()->where([
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
		])->count();
		if ( $number_chairn_time_out2>0) {
			$arr = [
				'id'     => $event->id,
				'name'   => $event->name,
				'number' => $number_chairn_time_out2,
			];
		}
		return $arr;
	}

	public static function getAllOwe($id) {
		$arr = [
			'id'     => null,
			'name'   => null,
			'number' => 0,
		];;
		$event    = Event::findOne($id);
		$countOwe = Order::find()->where([
			'>',
			'remain',
			0,
		])->andWhere([
			'event_id' => $event->id,
		])->andWhere([
			'<>',
			'status',
			0,
		])->count();
		if($countOwe > 0) {
			$arr = [
				'id'     => $event->id,
				'name'   => $event->name,
				'number' => $countOwe,
			];
		}
		return $arr;
	}

	public static function getAllCX($id) {
		$arr = [
			'id'     => null,
			'name'   => null,
			'number' => 0,
		];;
		$event   = Event::findOne($id);
		$countCX = Order::find()->where([
			'event_id' => $event->id,
			'status'   => 2,
		])->count();
		if ($countCX > 0) {
			$arr = [
				'id'     => $event->id,
				'name'   => $event->name,
				'number' => $countCX,
			];
		}
		return $arr;
	}

	public static function getSumOwe($id) {
		$arr = array();;
		$event   = Event::findOne($id);
		$countCX = Order::find()->where([
			'>',
			'remain',
			0,
		])->andWhere([
			'event_id' => $event->id,
		])->where(['<>','status',0])->count();
		if ($countCX > 0) {
			$sum = Order::find()->where([
				'>',
				'remain',
				0,
			])->andWhere([
				'event_id' => $event->id,
			])->where(['<>','status',0])->sum('remain');
			$a   = [
				'id'         => $event->id,
				'name'       => $event->name,
				'start_date' => $event->start_date,
				'end_date'   => $event->end_date,
				'number'     => number_format($sum) . "₫",
			];
			array_push($arr, $a);
		}
		return $arr;
	}

	public static function getAllArrChairTimeOut($id) {
		$date  = new DateTime();
		$event = Event::findOne($id);
		date_sub($date, date_interval_create_from_date_string('3 days'));

		$number_chairn_time_out2 = Order::find()->where([
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
		])->all();
		$arr                     = array();
		foreach($number_chairn_time_out2 as $item) {
			$a = [
				'date'         => $item->adjourn_date,
				'id'           => str_pad($item->number, 7, '0', STR_PAD_LEFT),
				'name'         => $item->customer_name,
				'total'        => number_format($item->grandTotal) . '₫',
				'adjourn_date' => $item->adjourn_date,
			];
			array_push($arr, $a);
		}
		return $provider = new ArrayDataProvider([
			'allModels'  => $arr,
			'pagination' => [
				'pageSize' => 10,
			],
		]);
	}

	public function getDiscountFilter() {
		$models = Order::find()->select('discount');
		if ($this->event_id !== null) {
			$models->where([
				'>',
				'discount',
				0,
			])->where([
				'status'   => 1,
				'event_id' => $this->event_id,
			]);
		} else {
			$models->where([
				'>',
				'discount',
				0,
			])->where(['status' => 1]);
		}
		$models = $models->orderBy(['discount' => SORT_ASC])->distinct(true)->all();
		$filter = [];
		foreach ($models as $model) {
			$a = [
				'id'    => $model->discount,
				'value' => $model->discount . '%',
			];
			array_push($filter, $a);
		}
		return $filter;
	}

    public function getExcelDescription() {
        $data   = array();
        $array  = array();
        $prices = Price::find()->where(['event_id' => $this->event_id])->all();
        /**@var Price $price**/
        foreach ($prices as $price) {
            $array[$price->id] = array(
                'count' => 0,
                'price' => $price->price,
            );
        }
        foreach ($this->orderSeats as $orderSeat) {
            $array[$orderSeat->price_id]['count'] ++;
        }
        foreach ($array as $key => $value) {
            if ($value['count'] != 0) {
                $data[$key]['price']    = $value['price'];
                $data[$key]['quantity'] = $value['count'];
                $data[$key]['total']    = $value['price'] * $value['count'];
                $data[$key]['seat']     = $this->getSeatString($key);
            }
        }
        return $data;
    }
}
