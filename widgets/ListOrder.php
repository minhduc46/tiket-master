<?php
namespace app\widgets;
use app\models\search\OrderSearch;
use Yii;
use yii\bootstrap\Widget;

class ListOrder extends Widget {

	public function run() {
		$searchModel  = new OrderSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		return $this->render('listorder', [
			'dataProvider' => $dataProvider,
		]);
	}
}