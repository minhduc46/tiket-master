<?php
namespace app\models;

use app\components\ActiveRecord;

/**
 * This is the model class for table "branch".
 *
 * @property integer $id
 * @property string  $name
 * @property string  $phone
 *
 * @property User[]  $users
 */
class Branch extends ActiveRecord {

	/**
	 * @inheritdoc
	 */
	public static function tableName() {
		return 'branch';
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
				],
				'required',
			],
			[
				[
					'name',
					'phone',
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
			'id'    => 'Đơn vị phân phối',
			'name'  => 'Tên đơn vị phân phối',
			'phone' => 'Điện thoại',
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getUsers() {
		return $this->hasMany(User::className(), ['branch_id' => 'id']);
	}
	public function getAllUser() {
		$users = array();
		foreach($this->users as $user) {
			$users[$user->id] = $user->username;
		}
		return $users;
	}
}
