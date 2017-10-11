<?php
/**
 * Created by PhpStorm.
 * User: lephuong
 * Date: 9/13/16
 * Time: 1:19 PM
 */
namespace app\components;

use Yii;

class Model extends \yii\base\Model {

	public function init() {
		parent::init();
	}

	public function rules() {
		return [
			[
				array_keys($this->attributes),
				'safe',
			],
		];
	}

	/**
	 * @return \yii\caching\Cache
	 */
	public function getCache() {
		return Yii::$app->getCache();
	}
}