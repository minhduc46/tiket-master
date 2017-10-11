<?php


use kartik\grid\GridView;
use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $searchModel app\models\search\BranchSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Phân phối';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="branch-index">
    <h3 class="header smaller lighter blue">Các đơn vị phân phối</h3>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel'  => $searchModel,
        'pjax'         => true,
        'bordered'     => true,
        'responsive'   => true,
        'rowOptions'   => [
            'style' => [
                'text-align' => 'center',
            ],
        ],
        'export'       => ['target' => GridView::TARGET_SELF],
        'toolbar'      => [
            [
                'content' => Html::a('<i class="glyphicon glyphicon-plus"></i>' . Yii::t('app', 'Thêm đơn vị phân phối'), ['create'], ['class' => 'btn btn-success']),
            ],
            '{export}',
            '{toggleData}',
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'name',
            'phone',

            ['class' => 'yii\grid\ActionColumn'],
        ],
        'panel'        => [
            'heading' => '<i><i class="glyphicon glyphicon-leaf"></i>' . Yii::t('app', 'Danh sách đơn vị phân phối') . '</i>',
            'footer'  => false,
        ],
    ]); ?>

</div>
