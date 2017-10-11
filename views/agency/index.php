<?php
use kartik\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\AgencySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title                   = 'Đại lý';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="agency-index">

	<div class="page-content">
		<div class="row-fluid">
			<div class="span12">
				<div class="row-fluid">
					<h3 class="header smaller lighter blue">Các đại lý bán</h3>
					<?= GridView::widget([
						'dataProvider' => $dataProvider,
						'filterModel'  => $searchModel,
						'bordered'     => true,
						'responsive'   => true,
						'rowOptions'   => [
							'style' => [
								'text-align' => 'center',
							],
						],
						'toolbar'      => [
							[
								'content' => Html::a('<i class="glyphicon glyphicon-plus"></i>' . Yii::t('app', 'Thêm đại lý'), ['create'], ['class' => 'btn btn-success']),
							],
							'{export}',
							'{toggleData}',
						],
						'columns'      => [
							['class' => 'yii\grid\SerialColumn'],
							'id',
							'name',
							'phone',
							'address',
							['class' => 'yii\grid\ActionColumn'],
						],
						'panel'        => [
							'heading' => '<i><i class="glyphicon glyphicon-leaf"></i>' . Yii::t('app', 'Danh sách đại lý') . '</i>',
							'footer'  => false,
						],
					]); ?>
				</div>
			</div>
		</div>
	</div>
</div>
