<?php
namespace app\controllers\user;

use app\models\User;
use dektrium\user\filters\AccessRule;
use navatech\role\filters\RoleFilter;
use yii\filters\AccessControl;

/**
 * Created by PhpStorm.
 * User: phuon
 * Date: 10/14/2016
 * Time: 10:09 AM
 */
class AdminController extends \dektrium\user\controllers\AdminController {

	/**
	 * @inheritdoc
	 */
	public function behaviors() {
		$behaviors                     = parent::behaviors();
		$behaviors['role']             = [
			'class'   => RoleFilter::className(),
			'name'    => 'Người Dùng',
			'actions' => [
				'index'  => "Danh sách",
				'view'   => "Xem",
				'create' => "Thêm",
				'update' => "Sửa",
				'delete' => "Xóa",
			],
		];
		$behaviors          ['access'] = [
			'class'      => AccessControl::className(),
			'ruleConfig' => [
				'class' => AccessRule::className(),
			],
			'rules'      => [
				[
					'allow' => true,
					'roles' => ['@'],
				],
			],
		];
		return $behaviors;
	}
	/**
	 * {@inheritDoc}
	 */
	public function actionIndex() {
		return parent::actionIndex();
	}
}