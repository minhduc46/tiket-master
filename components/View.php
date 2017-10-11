<?php
/**
 * Created by PhpStorm.
 * User: lephuong
 * Date: 9/14/16
 * Time: 11:05 AM
 */
namespace app\components;

use app\models\User;

class View extends \yii\web\View {

	/**@var User */
	public $user;

	public function init() {
		parent::init();
		$this->user = \Yii::$app->user->getIdentity();
	}
}