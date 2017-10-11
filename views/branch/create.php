<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Branch */

$this->title = 'Thêm đơn vị phân phối';
$this->params['breadcrumbs'][] = ['label' => 'Đơn vị phân phối', 'url' => ['index']];
$this->params['breadcrumbs'][] ='Thêm đơn vị phân phối';
?>
<div class="branch-create">
    <div class="page-header position-relative">
        <h1>
            Thêm mới đơn vị phân phối
        </h1>
    </div>


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
