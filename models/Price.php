<?php
namespace app\models;

use app\components\ActiveRecord;

/**
 * This is the model class for table "price".
 *
 * @property integer     $id
 * @property integer     $event_id
 * @property integer     $class
 * @property double      $price
 * @property string      $color
 *
 * @property OrderSeat[] $orderSeats
 * @property Event       $event
 */
class Price extends ActiveRecord {

	/**
	 * @inheritdoc
	 */
	public static function tableName() {
		return 'price';
	}

	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [
			[
				[
					'event_id',
					'class',
					'price',
					'color',
				],
				'required',
			],
			[
				[
					'event_id',
					'class',
				],
				'integer',
			],

			[
				['color'],
				'string',
				'max' => 255,
			],
			[
				['event_id'],
				'exist',
				'skipOnError'     => true,
				'targetClass'     => Event::className(),
				'targetAttribute' => ['event_id' => 'id'],
			],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {
		return [
			'id'       => 'ID',
			'event_id' => 'Tên sự kiện',
			'class'    => 'Lớp',
			'price'    => 'Giá',
			'color'    => 'Mầu',
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getOrderSeats() {
		return $this->hasMany(OrderSeat::className(), ['price_id' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getEvent() {
		return $this->hasOne(Event::className(), ['id' => 'event_id']);
	}
	public static function getColor($color = null) {
		if($color != null) {
			$response = Price::getColor();
			return isset($response[$color]) ? $response[$color] : '#ffffff';
		}
		return array(
			'#ff0000' => '#ff0000',
			'#ff0099' => '#ff0099',
			'#00c2ff' => '#00c2ff',
			'#00ba34' => '#00ba34',
			'#6966FF' => '#6966FF',
			'#9e2eba' => '#9e2eba',
			'#ff8a00' => '#ff8a00',
			'#ffe600' => '#ffe600',
			'#b3b3b3' => '#b3b3b3',
			'#FF7E7E' => '#FF7E7E',
			'#B36D3B' => '#B36D3B',
			'#328488' => '#328488',
			'#3EE4AE' => '#3EE4AE',
			'#6B6B6B' => '#6B6B6B',
			'#66EAFF' => '#66EAFF',
		);
	}
}
