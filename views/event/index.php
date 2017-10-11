<?php
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\EventSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title                   = 'Chương trình';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="event-index">

	<div class="page-content">
		<div class="row-fluid">
			<div class="span12">
				<div class="row-fluid">
					<h3 class="header smaller lighter blue">Các chương trình bán vé</h3>
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
								'content' => Html::a('<i class="glyphicon glyphicon-plus"></i>' . Yii::t('app', 'Thêm chương trình'), ['create'], ['class' => 'btn btn-success']),
							],
							'{export}',
							'{toggleData}',
						],
						'columns'      => [
							['class' => 'yii\grid\SerialColumn'],
							'name',
							[
								'attribute'           => 'start_date',
								'filterType'          => GridView::FILTER_DATE,
								'filterWidgetOptions' => [
									'pluginOptions' => [
										'autoclose' => true,
										'format'    => 'dd-mm-yyyy',
									],
								],
								'format'              => [
									'date',
									'php:d-m-Y',
								],
							],
							[
								'attribute'           => 'end_date',
								'filterType'          => GridView::FILTER_DATE,
								'filterWidgetOptions' => [
									'pluginOptions' => [
										'autoclose' => true,
										'format'    => 'dd-mm-yyyy',
									],
								],
								'format'              => [
									'date',
									'php:d-m-Y',
								],
							],
							[
								'attribute'           => 'status',
								'filterType'          => GridView::FILTER_SELECT2,
								'filter'              => [
									0 => 'Không',
									1 => 'Có',
								],
								'filterWidgetOptions' => [
									'options'       => ['placeholder' => 'Chọn trạng thái...'],
									'pluginOptions' => [
										'allowClear' => true,
									],
								],
								'value'               => function($data) {
									return $data->getTextStatus($data->status);
								},
							],
							// 'status',
							[
								'class'    => 'yii\grid\ActionColumn',
								'header'   => 'Hoạt động',
								'template' => '{map} {view} {update} {delete}',
								'buttons'  => [
									//view button
									'map' => function($url, $model) {
										return Html::a('<span class="glyphicon glyphicon-map-marker"></span>', Url::toRoute([
											'/map/view',
											'id' => $model->id,
										]), [
											'title' => Yii::t('app', 'Map'),
										]);
									},
								],
							],
						],
						'panel'        => [
							'heading' => '<i><i class="glyphicon glyphicon-leaf"></i>' . Yii::t('app', 'Danh sách chương trình') . '</i>',
							'footer'  => false,
						],
					]); ?>
				</div>
			</div>
		</div>
	</div>
</div>
