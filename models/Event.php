<?php
namespace app\models;

use app\components\ActiveRecord;

/**
 * This is the model class for table "event".
 *
 * @property integer     $id
 * @property string      $name
 * @property string      $create_date
 * @property string      $start_date
 * @property string      $end_date
 * @property integer     $status
 *
 * @property EventUser[] $eventUsers
 * @property Order[]     $orders
 * @property Price[]     $prices
 */
class Event extends ActiveRecord {

	/**
	 * @inheritdoc
	 */
	public static function tableName() {
		return 'event';
	}

	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [
			[
				[
					'name',
					'start_date',
					'end_date',
				],
				'required',
			],
			[
				[
					'create_date',
					'start_date',
					'end_date',
				],
				'safe',
			],
			[
				['status'],
				'integer',
			],
			[
				['name'],
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
			'id'          => 'ID',
			'name'        => 'Tên chương trình',
			'create_date' => 'Ngày tạo',
			'start_date'  => 'Ngày bắt đầu',
			'end_date'    => 'Ngày kết thúc',
			'status'      => 'Trạng thái',
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getEventUsers() {
		return $this->hasMany(EventUser::className(), ['event_id' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getOrders() {
		return $this->hasMany(Order::className(), ['event_id' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getPrices() {
		return $this->hasMany(Price::className(), ['event_id' => 'id']);
	}

	public function getTextStatus($status) {
		$arrStatus = [
			0 => 'Không',
			1 => 'Có',
		];
		if (isset($arrStatus[$status])) {
			return $arrStatus[$status];
		} else {
			return "Không tìm thấy";
		}
	}

	public function validateTime() {
		if ($this->start_date > date("Y-m-d")) {
			return 1;
		} elseif ($this->end_date < date("Y-m-d")) {
			return 2;
		}
		return 0;
	}

	public function beforeDelete() {
		Price::deleteAll(['event_id' => $this->id]);
		return parent::beforeDelete();
	}

}