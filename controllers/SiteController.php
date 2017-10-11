<?php
namespace app\controllers;

use app\components\Controller;
use app\models\Event;
use app\models\EventUser;
use app\models\LoginForm;
use app\test\Action;
use app\test\YiiBase;
use Yii;
use yii\filters\AccessControl;

class SiteController extends Controller {

	public function behaviors() {
		return [
			'access' => [
				'class' => AccessControl::className(),
				'rules' => [
					[
						'actions' => [
							'login',
							'error',
						],
						'allow'   => true,
					],
					[
						'actions' => [
							'index',
							'logout',
							'test',
						],
						'allow'   => true,
						'roles'   => ['@'],
					],
				],
			],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function actions() {
		return [
			'error'   => [
				'class' => 'yii\web\ErrorAction',
			],
			'captcha' => [
				'class'           => 'yii\captcha\CaptchaAction',
				'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
			],
		];
	}

	/**
	 * Displays homepage.
	 *
	 * @return string
	 */
	public function actionIndex() {

		if (Yii::$app->user->id) {
			if ($this->user->role_id == 1) {
				$events = Event::find()->where(["status" => 1])->andWhere([
					'<=',
					'start_date',
					date('Y-m-d'),
				])->andWhere([
					'>=',
					'end_date',
					date('Y-m-d'),
				])->orderBy(['start_date' => SORT_DESC])->all();
			} else {
				$events = EventUser::getEvents(Yii::$app->user->id);
			}
			return $this->render('index', [
				'events' => $events,
			]);
		} else {
			return $this->redirect(['site/login']);
		}
	}


    public function actionLogin() {

		if (!Yii::$app->user->isGuest) {
			return $this->goHome();
		}
		$this->layout = 'login';
		$model        = new LoginForm();
		if ($model->load(Yii::$app->request->post())) {
			if ($model->login()) {
				return $this->goBack();
			}
		} else {
			return $this->render('login', [
				'model' => $model,
			]);
		}
	}
}
