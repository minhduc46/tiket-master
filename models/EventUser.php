<?php
namespace app\models;

use app\components\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "event_user".
 *
 * @property integer $id
 * @property integer $event_id
 * @property integer $user_id
 *
 * @property Event   $event
 * @property User    $user
 */
class EventUser extends ActiveRecord {

	/**
	 * @inheritdoc
	 */
	public static function tableName() {
		return 'event_user';
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
				],
				'required',
			],
			[
				[
					'event_id',
					'user_id',
				],
				'integer',
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
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {
		return [
			'id'       => 'id',
			'event_id' => 'Tên sự kiện',
			'user_id'  => 'Người dùng',
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
	 * @param $user_id
	 *
	 * @return array|\yii\db\ActiveRecord[]
	 */
	public static function getEvents($user_id) {
		/**@var EventUser[] $models */
		$event_id = ArrayHelper::map(EventUser::find()->where(['user_id' => $user_id])->all(), 'id', 'event_id');
		return Event::find()->where([
			'id'     => $event_id,
			'status' => 1,
		])->andWhere([
			'<=',
			'start_date',
			date('Y-m-d'),
		])->andWhere([
			'>=',
			'end_date',
			date('Y-m-d'),
		])->orderBy(['start_date' => SORT_DESC])->all();
	}
}
