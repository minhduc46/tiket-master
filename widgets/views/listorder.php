<?php
use app\models\Event;
use app\models\Order;
use app\models\User;
use kartik\editable\Editable;
use kartik\grid\GridView;
use yii\bootstrap\Html;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;



?>

<div class="ace-settings-container" id="ace-settings-container">
    <div class="btn btn-app btn-xs btn-warning ace-settings-btn" id="ace-settings-btn">
        <i class="fa fa-history" aria-hidden="true"></i>
    </div>
    <div class="ace-settings-box clearfix" id="ace-settings-box">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'responsive' => true,
            'export' => false,
            'rowOptions' => [
                'style' => [
                    'text-align' => 'center',
                ],
            ],
            'toolbar' => false,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'attribute' => 'booked_date',
                    'filter' => false,
                    'format' => [
                        'date',
                        'php:d-m-Y',
                    ],
                ],
                [
                    'attribute' => 'user_id',
                    'filter' => false,
                    'value' => function ($data) {
                        return $data->getTextUser($data->user_id);
                    },
                ],
                [
                    'attribute' => 'number',
                    'filter' => false,
                ],
                [
                    'class' => 'kartik\grid\FormulaColumn',
                    'header' => "Tổng số ghế",
                    'format' => 'html',
                    'value' => function ($data) {
                        return count($data->orderSeats);
                    },
                    'footer' => true,
                ],
                [
                    'class' => 'kartik\grid\FormulaColumn',
                    'header' => 'Tổng tiền',
                    'value' => function ($model, $key, $index, $widget) {
                        return number_format($model->grandTotal) . "₫";
                    },
                    'footer' => true,
                ],
                [
                    'attribute' => 'remain',
                    'value' => function ($data) {
                        return number_format($data->remain) . "₫";
                    },
                ],
                [
                    'attribute' => 'status',
                    'value' => function ($data) {
                        return Order::getArrStatus()[$data->status];
                    },
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{view}',
                    'buttons' => [
                        //view button
                        'view' => function ($url, $model) {
                            return Html::a('<span class="fa fa-search"></span>', \yii\helpers\Url::to([
                                'view',
                                'number' => $model->number,
                                'event_id' => $model->event_id,
                            ]), [
                                'title' => Yii::t('app', 'Xem chi tiết'),
                            ]);
                        },
                    ],
                ],
            ],
            'panel' => [
                'heading' => '<i><i class="glyphicon glyphicon-leaf"></i>' . Yii::t('app', 'Danh sách đơn hàng') . '</i>',
                'footer' => false,
            ],
        ]); ?>
    </div><!-- /.ace-settings-box -->
</div><!-- /.ace-settings-container -->


<script>
    $(document).ready(function () {
        $(document).on("click", "#ace-settings-btn", function () {
            $(this).toggleClass("open");
            $("#ace-settings-box").toggleClass("open");
            return false;
        });


    })

</script>
