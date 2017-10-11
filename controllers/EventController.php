<?php
namespace app\controllers;

use app\models\EventUser;
use app\models\Price;
use app\models\search\EventSearch;
use app\models\User;
use navatech\role\filters\RoleFilter;
use Yii;
use app\models\Event;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UserEvent;
use yii\widgets\ActiveForm;

/**
 * EventController implements the CRUD actions for Event model.
 */
class EventController extends Controller {

	/**
	 * @inheritdoc
	 */
	public function behaviors() {
		return [
			'verbs' => [
				'class'   => VerbFilter::className(),
				'actions' => [
					'delete' => ['POST'],
				],
			],
			'role'  => [
				'class'   => RoleFilter::className(),
				'name'    => "Sự kiện",
				'actions' => [
					'index'            => "Danh sách",
					'create'           => "Thêm",
					'update'           => "Sửa",
					'delete'           => "Xóa",
				],
			],
		];
	}

	/**
	 * Lists all Event models.
	 * @return mixed
	 */
	public function actionIndex() {
		$searchModel  = new EventSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		return $this->render('index', [
			'searchModel'  => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}

	/**
	 * Displays a single Event model.
	 *
	 * @param integer $id
	 *
	 * @return mixed
	 */
	public function actionView($id) {
		$model = $this->findModel($id);
		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->render('view', [
				'model' => $model,
			]);
		} else {
			return $this->render('view', [
				'model' => $model,
			]);
		}
	}

	/**
	 * Creates a new Event model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	protected function performAjaxValidation($model) {
		if(isset($_POST['ajax']) && $_POST['ajax'] === 'event-form') {
			ActiveForm::validate($model);
			//echo ::validate($model);
			Yii::$app->end();
		}
	}

	/**
	 * convert data arr to string seperate ','
	 * @param $arrUser
	 * return $str type string
	 */
	protected function convertArrayUserToString($arrUser)
	{
		$str='';
		for($i=0;$i<count($arrUser)-1;$i++)
		{
			$str.=$arrUser[$i].",";
		}
		if($arrUser!=null)
			$str.=$arrUser[count($arrUser)-1];
		return $str;
	}

	public function actionCreate() {
		$model              = new Event();

		$prices             = array();
		$model->start_date  = date("Y-m-d");
		$model->end_date    = date("Y-m-d");
		$model->create_date = date("Y-m-d");
		$user=new EventUser();
		for($i = 0; $i < count(Price::getColor()); $i ++) {
			$prices[] = new Price();
		}
		if(isset($_POST['Event']) && isset($_POST['Price']) && isset($_POST['EventUser'])) {
			$model->attributes = $_POST['Event'];
			$user->user_id=$_POST['EventUser']['user_id'];
			if(strtotime($model->start_date) >= strtotime($model->end_date)) {
				$model->addError('start_date', 'Ngày bắt đầu không được lớn hơn ngày kết thúc');
				$model->addError('end_date', 'Ngày kết thúc không được nhỏ hơn ngày bắt đầu');
			}
			else
			{

				if($model->save())
				{
					foreach($_POST['EventUser']['user_id'] as $item) {
						$user1=new EventUser();
						$user1->event_id = $model->id;
						$user1->user_id  = $item;
						$user1->save();
					}
					foreach($_POST['Price'] as $postPrice) {
						$price             = new Price();
						$price->attributes = $postPrice;
						if($postPrice['price'] == '') {
							$price->price = 0;
						} else {
							$price->price = (int)str_replace(',', '', $postPrice['price']);
						}
						$price->event_id = $model->id;
						$price->save();
					}
				    return	$this->redirect(array(
						'view',
						'id' => $model->id
					));
				}

			}

            return $this->render('create', [
                'model'  => $model,
                'user'   => $user,
                'prices' => $prices,
            ]);
		}
		else
		return $this->render('create', [
			'model'  => $model,
			'user'   => $user,
			'prices' => $prices,
		]);
	}

	/**
	 * Updates an existing Event model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 *
	 * @param integer $id
	 *
	 * @return mixed
	 */
	public function actionUpdate($id) {
		$model = $this->findModel($id);
		$prices=Price::find()->where(['event_id'=>$id])->orderBy(['class'=>SORT_ASC])->all();
		$user=EventUser::findOne(['event_id'=>$id]);
		$eventUser=EventUser::find()->where(['event_id'=>$id])->all();

		$arr=[];

		foreach($eventUser as $item)
		{
			$a=[$item->user_id];
			$arr=array_merge($arr,$a);
		}
		$user->user_id=$arr;

	/*	$user->user_id=explode(",",$user->user_id);*/

		if(isset($_POST['Event']) && isset($_POST['Price']) && isset($_POST['EventUser'])) {
			$model->attributes = $_POST['Event'];
			$user->user_id=$_POST['EventUser']['user_id'];
			if(strtotime($model->start_date) >= strtotime($model->end_date)) {
				$model->addError('start_date', 'Ngày bắt đầu không được lớn hơn ngày kết thúc');
				$model->addError('end_date', 'Ngày kết thúc không được nhỏ hơn ngày bắt đầu');
			}
			else
			{
				if($model->save())
				{
					EventUser::deleteAll(['event_id'=>$id]);
					foreach($_POST['EventUser']['user_id'] as $item) {
						$user1=new EventUser();
						$user1->event_id = $model->id;
						$user1->user_id  = $item;
						$user1->save();
					}
					foreach($_POST['Price'] as $postPrice) {
						$price             = new Price();
						$price->attributes = $postPrice;
						if($postPrice['price'] == '') {
							$price->price = 0;
						} else {
							$price->price = (int)str_replace(',', '', $postPrice['price']);
						}
						$price->event_id = $model->id;
						$price1=Price::findOne(['event_id'=>$model->id,'class'=>$price->class]);
						$price1->color=$price->color;
						$price1->price=$price->price;
						$price1->save();
					}
					$this->redirect(array(
						'view',
						'id' => $model->id
					));
				}
			}
		}
		if($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect([
				'view',
				'id' => $model->id,
			]);
		} else {
			return $this->render('update', [
				'model' => $model,
				'user'=>$user,
				'prices'=>$prices
			]);
		}
	}

	/**
	 * Deletes an existing Event model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 *
	 * @param integer $id
	 *
	 * @return mixed
	 */
	public function actionDelete($id) {
		$this->findModel($id)->delete();
		return $this->redirect(['index']);
	}

	/**
	 * Finds the Event model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 *
	 * @param integer $id
	 *
	 * @return Event the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id) {
		if(($model = Event::findOne($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}
}
