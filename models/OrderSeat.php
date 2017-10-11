<?php
namespace app\models;

use app\components\ActiveRecord;

/**
 * This is the model class for table "order_seat".
 *
 * @property integer $id
 * @property integer $order_id
 * @property integer $price_id
 * @property string  $row
 * @property integer $number
 * @property integer $floor
 * @property integer $status
 *
 * @property Order   $order
 * @property Price   $price
 */
class OrderSeat extends ActiveRecord {

	/**
	 * @inheritdoc
	 */
	public static function tableName() {
		return 'order_seat';
	}

	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [
			[
				[
					'order_id',
					'price_id',
					'row',
					'number',
					'floor',
				],
				'required',
			],
			[
				[
					'order_id',
					'price_id',
					'number',
					'floor',
					'status',
				],
				'integer',
			],
			[
				['row'],
				'string',
				'max' => 255,
			],
			[
				['order_id'],
				'exist',
				'skipOnError'     => true,
				'targetClass'     => Order::className(),
				'targetAttribute' => ['order_id' => 'id'],
			],
			[
				['price_id'],
				'exist',
				'skipOnError'     => true,
				'targetClass'     => Price::className(),
				'targetAttribute' => ['price_id' => 'id'],
			],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {
		return [
			'id'       => 'ID',
			'order_id' => 'Đơn hàng',
			'price_id' => 'Giá',
			'row'      => 'Hàng',
			'number'   => 'Số lượng',
			'floor'    => 'Sàn',
			'status'   => 'Trạng thái',
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getOrder() {
		return $this->hasOne(Order::className(), ['id' => 'order_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getPrice() {
		return $this->hasOne(Price::className(), ['id' => 'price_id']);
	}
}
