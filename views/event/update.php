<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Event */

$this->title = 'Cập nhật chương trình: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Chương trình', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Cập nhật chương trình';
?>
<div class="event-update">

    <div class="page-header position-relative">
        <h1>
            Cập nhật chương trình
        </h1>
    </div>

	<?= $this->render('_form', [
		'model' => $model,
		'user'=>$user,
		'prices'=>$prices
	]) ?>

</div>
