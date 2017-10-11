<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Agency */
$this->title = 'Cập nhật đại lý: ' . $model->name;
$this->params['breadcrumbs'][] = [
	'label' => 'Danh sách đại lý',
	'url'   => ['index'],
];
$this->params['breadcrumbs'][] = [
	'label' => $model->name,
	'url'   => [
		'view',
		'id' => $model->id,
	],
];
$this->params['breadcrumbs'][] = 'Cập nhật';
?>
<div class="agency-update">

	<div class="page-header position-relative">
		<h1><?= Html::encode($this->title) ?></h1>
	</div>

	<?= $this->render('_form', [
		'model' => $model,
	]) ?>

</div>
