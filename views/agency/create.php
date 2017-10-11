<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Agency */
$this->title = 'Thêm đại lí';
$this->params['breadcrumbs'][] = [
	'label' => 'Đại lý',
	'url'   => ['index'],
];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="agency-create">

	<h1> <div class="page-header position-relative">
			<h1>
				Thêm mới đại lý
			</h1>
		</div>

	<?= $this->render('_form', [
		'model' => $model,
	]) ?>

</div>
