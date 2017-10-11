<?php
/**
 * Created by PhpStorm.
 * User: lephuong
 * Date: 9/13/16
 * Time: 1:19 PM
 */
namespace app\components;

use app\models\User;
use yii\caching\Cache;

class Controller extends \yii\web\Controller {

	/**@var User */
	public $user;

	/**@var Cache */
	public $cache;

	public function init() {
		parent::init();
		$this->cache = \Yii::$app->cache;
		$this->user  = \Yii::$app->user->getIdentity();
	}
}