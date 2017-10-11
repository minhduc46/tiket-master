<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Branch */

$this->title = 'Cập nhật đơn vị phân phối: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Đơn vị phân phối', 'url' => ['index']];

$this->params['breadcrumbs'][] = 'cập nhật đơn vị phân phối';
?>
<div class="branch-update">

    <div class="page-header position-relative">
        <h1>
           Cập nhật đơn vị phân phối
        </h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
