<?php
namespace app\models;

use app\components\ActiveRecord;

/**
 * This is the model class for table "agency".
 *
 * @property integer $id
 * @property string  $name
 * @property string  $phone
 * @property string  $address
 *
 * @property Order[] $orders
 */
class Agency extends ActiveRecord {

	/**
	 * @inheritdoc
	 */
	public static function tableName() {
		return 'agency';
	}

	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [
			[
				[
					'name',
					'phone',
					'address',
				],
				'required',
			],
			[
				[
					'name',
					'phone',
					'address',
				],
				'string',
				'max' => 255,
			],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {
		return [
			'id'      => 'ID',
			'name'    => 'Tên đại lý',
			'phone'   => 'Điện thoại',
			'address' => 'Địa chỉ',
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getOrders() {
		return $this->hasMany(Order::className(), ['agency_id' => 'id']);
	}
}
