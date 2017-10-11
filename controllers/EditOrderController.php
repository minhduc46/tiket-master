<?php
namespace app\controllers;

use app\components\Controller;
use app\models\Event;
use app\models\EventUser;
use app\models\LoginForm;
use app\models\Order;
use DateTime;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\Json;

class EditOrderController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => [
                            'login',
                            'error',
                        ],
                        'allow' => true,
                    ],
                    [
                        'actions' => [
                            'remain',
                            'status',
                            'adjourn',
                        ],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionRemain($event_id, $number)
    {
        if (isset($_POST['Order'])) {
        	$i=0;
            $remain = $_POST['Order'][0]['remain'];
            $orders = Order::find()->where(['event_id' => $event_id, 'number' => $number])->all();
            foreach ($orders as $order) {
                $ob = Order::findOne($order->id);
                $ob->remain = $remain;
                if (!$ob->save()) {
	              $i=1;
                }
            }
            if($i==0)
            {
	            $arrr = array('output' => number_format($remain).'đ','message' => '');
	            echo Json::encode($arrr);
            }
            else
            {
	            $arrr = array('output' => '','message' => 'Lỗi');
	            echo Json::encode($arrr);
            }
        } else {
            $arrr = array('message' => 'Lỗi', 'output' => '');
            echo Json::encode($arrr);
        }
    }

    public function actionStatus($event_id, $number)
    {
        if (isset($_POST['Order'])) {
            $status = $_POST['Order'][0]['status'];
            $orders = Order::find()->where(['event_id' => $event_id, 'number' => $number])->andWhere(['<>', 'status', 0])->all();
            $i=0;
            foreach ($orders as $order) {
                $ob = Order::findOne($order->id);
                $ob->status = $status;
                if (!$ob->save()) {
                $i=1;
                }
            }
            if($i==0)
            {
	            $arrr = ['message' => '', 'output' => Order::getArrStatus()[$status]];
	            echo Json::encode($arrr);
            }
            else
            {
	            $arrr = ['message' => 'Lỗi', 'output' => ''];
	            echo Json::encode($arrr);
            }
        } else {
            $arrr = ['message' => 'Lỗi', 'output' => ''];
            echo Json::encode($arrr);
        }
    }

    public function actionAdjourn($event_id, $number)
    {
        if (isset($_POST['Order'])) {
            $adjourn = $_POST['Order'][0]['adjourn_date'];
            $orders = Order::find()->where(['event_id' => $event_id, 'number' => $number])->andWhere(['<>', 'status', 0])->all();
            $i=0;
            foreach ($orders as $order) {
                $ob = Order::findOne($order->id);
                $time = strtotime($adjourn);
                $newformat = date('Y-m-d', $time);
                $ob->adjourn_date = $newformat;
                if (!$ob->save()) {
                   $i=1;
                }
            }
            if($i==0)
            {
	            $arrr = ['message' => '', 'output' => $newformat = DateTime::createFromFormat('Y-m-d', $ob->adjourn_date)->format('d-m-Y')];
	            echo Json::encode($arrr);
            }
            else
            {
	            $arrr = ['message' => 'Lỗi', 'output' =>''];
	            echo Json::encode($arrr);
            }
        } else {
            $arrr = ['message' => 'Lỗi', 'output' => ''];
            echo Json::encode($arrr);
        }
    }

}
