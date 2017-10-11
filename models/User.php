<?php
/**
 * Created by Navatech
 * @project ticket-ibgroup-vn
 * @author  Le Phuong
 * @email phuong17889@gmail.com
 * @time    2/6/2017 4:42 PM
 */
namespace app\models;

use Yii;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "user".
 *
 * @property integer         $id
 * @property integer         $role_id
 * @property integer         $branch_id
 * @property string          $username
 * @property string          $email
 * @property string          $password_hash
 * @property string          $auth_key
 * @property integer         $confirmed_at
 * @property string          $unconfirmed_email
 * @property integer         $blocked_at
 * @property string          $registration_ip
 * @property integer         $created_at
 * @property integer         $updated_at
 * @property integer         $flags
 * @property integer         $last_login_at
 *
 * @property EventUser[]     $eventUsers
 * @property Order[]         $orders
 * @property Profile         $profile
 * @property SocialAccount[] $socialAccounts
 * @property Token[]         $tokens
 * @property Role            $role
 * @property Branch          $branch
 */
class User extends \navatech\role\models\User {

	/**
	 * @inheritdoc
	 */
	public function rules() {
		$rules = parent::rules();
		return ArrayHelper::merge($rules, [
			[
				[
					'branch_id',
				],
				'integer',
			],
			[
				[
					'branch_id',
				],
				'required',
			],
			[
				[
					'branch_id',
				],
				'safe',
			],
			[
				['branch_id'],
				'exist',
				'skipOnError'     => true,
				'targetClass'     => Branch::className(),
				'targetAttribute' => ['branch_id' => 'id'],
			],
		]);
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {
		$attributeLabels = parent::attributeLabels();
		return ArrayHelper::merge($attributeLabels, [
			'branch_id' => 'Branch ID',
		]);
	}

	/**
	 * @return ActiveQuery
	 */
	public function getEventUsers() {
		return $this->hasMany(EventUser::className(), ['user_id' => 'id']);
	}

	/**
	 * @return ActiveQuery
	 */
	public function getOrders() {
		return $this->hasMany(Order::className(), ['user_id' => 'id']);
	}

	/**
	 * @return ActiveQuery
	 */
	public function getBranch() {
		return $this->hasOne(Branch::className(), ['id' => 'branch_id']);
	}

	/**
	 * @return ActiveQuery
	 */
	public function getEvents() {
		return $this->hasMany(Event::className(), ['id' => 'event_id'])->viaTable('event_user', ['user_id' => 'id']);
	}
	public static function getAllUser($excludeAdmin = true) {
		if($excludeAdmin) {
			//$models = $this->findAll("user_id <> 1");
			return User::find()->where(['<>','id',1])->asArray()->all();
		} else {
			return  User::find()->asArray()->all();
		}

	}


	/**
	 * Hàm trả về kết quả TRUE or FALSE của Role hiện tại có được quyền truy cập hay không?
	 *
	 * @param string $controller Tên controller được truyền vào, nếu truyền rỗng thì $controller sẽ mặc định bằng Yii::app()->controller->id
	 * @param string $action Tên action được truyền vào, nếu truyền rỗng thì $action sẽ mặc định bằng Yii::app()->controller->action->id
	 * @param int    $user_role_id Số Role, nếu truyền rỗng thì $user_role_id sẽ mặc định bằng null
	 *
	 * @return bool được cấp phép hay không
	 */
	public function access($controller = null, $action = null, $user_role_id = null) {
		if($user_role_id == null && Yii::$app->user->id && Yii::$app->user->identity->getRoleId() == 1) {
			return true;
		}
		if($user_role_id == null) {
			$user_role_id=Yii::$app->session->get("user_role_id");
		}

		$model = Role::findOne($user_role_id);
		/*if($controller == null) {
			return $model->getPermission();
		} else {
			return $model->getPermission($controller, $action);
		}*/
	}
}
