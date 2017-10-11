<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Event */

$this->title = 'Thêm chương trình';
$this->params['breadcrumbs'][] = ['label' => 'Chương trình', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="event-create">

    <div class="page-header position-relative">
        <h1>
            Thêm mới chương trình
        </h1>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
	    'user'=>$user,
	    'prices'=>$prices
    ]) ?>

</div>
