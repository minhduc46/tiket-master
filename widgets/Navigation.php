<?php
/**
 * Created by PhpStorm.
 * Author: Phuong
 * Email: notteen@gmail.com
 * Date: 16/02/2017
 * Time: 5:07 CH
 */
namespace app\widgets;

use app\models\Order;
use DateTime;
use Yii;
use yii\base\Widget;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;

class Navigation extends Widget {

	public function run() {
		$controller = Yii::$app->controller->id;
		$action     = Yii::$app->controller->action->id;
		if($controller == 'map' && $action == 'view') {
			$event = Yii::$app->request->queryParams['id'];
			$date  = new DateTime();
			date_sub($date, date_interval_create_from_date_string('3 days'));
			$query_chair_time_out        = $number_chairn_time_out2 = Order::find()->where([
				'<',
				'adjourn_date',
				date_format($date, 'Y-m-d'),
			])->andWhere([
				'<>',
				'adjourn_date',
				'0000-00-00',
			])->andWhere([
				'status'   => 1,
				'event_id' => $event,
			]);
			$dataProvider_chair_time_out = new ActiveDataProvider([
				'query'      => $query_chair_time_out,
				'pagination' => [
					'pageSize' => 10,
				],
			]);
			$provider                    = new ArrayDataProvider([
				'allModels'  => Order::getSumOwe($event),
				'pagination' => [
					'pageSize' => 10,
				],
			]);
			$date                        = new DateTime();
			date_add($date, date_interval_create_from_date_string('3 days'));
			$query = Order::find()->where([
				'>',
				'remain',
				0,
			])->andWhere([
				'event_id' => $event,
			])->andWhere([
				'<>',
				'status',
				0,
			])->limit(10);
			// add conditions that should always apply here
			$dataProvider = new ActiveDataProvider([
				'query'      => $query,
				'pagination' => [
					'pageSize' => 10,
				],
			]);
			//Chưa xuất kho
			$query1 = Order::find()->andWhere([
				'event_id' => $event,
				'status'   => Order::STATUS_CHUAXUAT,
			])->limit(10);
			// add conditions that should always apply here
			$dataProvider1 = new ActiveDataProvider([
				'query'      => $query1,
				'pagination' => [
					'pageSize' => 10,
				],
			]);
			return $this->render('navigation', [
				'dataProvider_chair_time_out' => $dataProvider_chair_time_out,
				'provider'=>$provider,
				'dataProvider'=> $dataProvider,
				'dataProvider1'=> $dataProvider1,
				'event'=>$event,
				'type'=>1
			]);
		}
		else
		{
			return $this->render('navigation', [

				'type'=>2
			]);
		}

	}
}