<?php
use yii\helpers\Html;
use kartik\detail\DetailView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Agency */
$this->title                   = $model->name;
$this->params['breadcrumbs'][] = [
	'label' => 'Danh sách đại lý',
	'url'   => ['index'],
];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="agency-view">

	<h1><?= Html::encode($this->title) ?></h1>

	<?= DetailView::widget([
		'mode' => DetailView::MODE_VIEW,
		'bordered' => true,
		'striped' => true,
		'condensed' => true,
		'responsive' => true,
		'hover' => true,
		'hAlign' => 'right',
		'vAlign' => 'middle',
		'fadeDelay' => 809,
		'model' => $model,
		'deleteOptions'      => [
			'Xóa',
			'id' => $model->name,
			'url'=>Url::to(['delete','id'=>$model->id]),
			'confirm'=>'Bạn có muốn xóa đối tượng này không?'
		],
		'panel' => [
			'heading' => '<i class="fa fa-books"></i> ' . Yii::t('app', 'Chi tiết đơn vị phân phối')
			, 'type' => DetailView::TYPE_SUCCESS,
			'footer' => false,
		],
		'attributes' => [
			[
				'group' => true,
				'label' => 'Thông tin chính',
				'rowOptions' => ['class' => DetailView::TYPE_INFO],
			],
			[
				'attribute' => 	'id',
				'type' => DetailView::INPUT_HIDDEN,
				'valueColOptions' => ['style' => 'width:70%'],

			],

			'name',
			'phone',
			'address',
		],
	]) ?>

</div>
