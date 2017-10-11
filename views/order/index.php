<?php
use app\models\Event;
use app\models\Order;
use app\models\User;
use kartik\grid\GridView;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = 'Hóa đơn';
$this->params['breadcrumbs'][] = $this->title;
$bundle = \app\assets\AppAsset::register($this);
$order = new Order();
?>
    <link rel="stylesheet" href="<?= $bundle->baseUrl ?>/web/css/order.css">

    </link>
    <div class="order-index">
        <div class="page-content">
            <div class="row-fluid">
                <div class="span12">
                    <div class="row-fluid">
                        <h3 class="header smaller lighter blue">Các hóa đơn bán vé</h3>

                        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
                        <?= GridView::widget([
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,
                            'bordered' => true,
                            'responsive' => true,
                            'striped' => true,
                            'rowOptions' => [
                                'style' => [
                                    'text-align' => 'center',
                                ],
                            ],
                            'export' => ['target' => GridView::TARGET_SELF],
                            'toolbar' => [

                                [
                                    'content' => Html::button('<i class="glyphicon glyphicon-resize-full"></i> Toàn bộ', [
                                            'type' => 'button',
                                            'title' => 'Toàn bộ',
                                            'class' => 'btn btn-default button-error',
                                        ]) . ' ' .
                                        Html::a('<i class="fa fa-file-excel-o">Xuất excel</i>', Url::to(['excel','event_id'=>$event_id]),
                                            [ 'title' => 'Xuất excel',
                                                'class' => 'btn btn-success'])

                                ],
                                '{toggleData}'
                            ],
                            'columns' => [
                                ['class' => 'yii\grid\SerialColumn'],
                                ['attribute' => 'id'],
                                [
                                    'attribute' => 'booked_date',
                                    'filterType' => GridView::FILTER_DATE,
                                    'filterWidgetOptions' => [
                                        'pluginOptions' => [
                                            'autoclose' => true,
                                            'format' => 'dd-mm-yyyy',
                                        ],
                                    ],
                                    'format' => [
                                        'date',
                                        'php:d-m-Y',
                                    ],
                                ],
                                [
                                    'attribute' => 'event_id',
                                    'filterType' => GridView::FILTER_SELECT2,
                                    'filter' => ArrayHelper::map(Event::find()->asArray()->all(), 'id', 'name'),
                                    'filterWidgetOptions' => [
                                        'options' => ['placeholder' => 'Chọn sự kiện...'],
                                        'pluginOptions' => [
                                            'allowClear' => true,
                                        ],
                                    ],
                                    'value' => function (Order $data) {
                                        return $data->event->name;
                                    },
                                ],
                                [
                                    'class' => 'kartik\grid\FormulaColumn',
                                    'header' => 'Đơn vị phân phối',
                                    'vAlign' => 'middle',
                                    'value' => function (Order $model, $key, $index, $widget) {
                                        return $model->user->branch->name;
                                    },
                                    'headerOptions' => ['class' => 'kartik-sheet-style'],
                                    'hAlign' => 'right',
                                    'width' => '5%',
                                    'mergeHeader' => true,
                                    'footer' => true,
                                ],
                                [
                                    'attribute' => 'user_id',
                                    'filterType' => GridView::FILTER_SELECT2,
                                    'filterWidgetOptions' => [
                                        'options' => ['prompt' => 'Chọn'],
                                        'pluginOptions' => [
                                            'allowClear' => true,
                                        ],
                                    ],
                                    'value' => function (Order $data) {
                                        return $data->user->username;
                                    },
                                ],
                                [
                                    'attribute' => 'number',
                                    'value' => function (Order $data) {
                                        return str_pad($data->number, 7, '0', STR_PAD_LEFT);
                                    },
                                ],
                                'customer_name',
                                [
                                    'class' => 'kartik\grid\FormulaColumn',
                                    'header' => "<div class='order-detail header' style='text-align: center'>Chi tiết đơn hàng</div><div class='order-detail col-xs-12'><span class='col-xs-2'>Loại vé</span><span class='col-xs-2'>SL</span><span class='col-xs-5'>Vị trí</span><span class='col-xs-3'>Thành tiền</span></div>",
                                    'format' => 'html',
                                    'value' => function (Order $data) {
                                        return $data->getDescription();
                                    },
                                    'headerOptions' => ['class' => 'kartik-sheet-style'],
                                    'hAlign' => 'right',
                                    'width' => '30%',
                                    'mergeHeader' => true,
                                    'footer' => true,
                                ],
                                [
                                    'class' => 'kartik\grid\FormulaColumn',
                                    'header' => 'Tiền vé',
                                    'vAlign' => 'middle',
                                    'value' => function ($model, $key, $index, $widget) {
                                        return number_format($model->subTotal) . "₫";
                                    },
                                    'headerOptions' => ['class' => 'kartik-sheet-style'],
                                    'hAlign' => 'right',
                                    'width' => '7%',
                                    'mergeHeader' => true,
                                    'footer' => true,
                                ],
                                [
                                    'attribute' => 'discount',
                                    'value' => function (Order $model) {
                                        return number_format($model->discount) . $model->getDiscountType();
                                    },
                                    'filterType' => GridView::FILTER_SELECT2,
                                    'filter' => $a1,
                                    'filterWidgetOptions' => [
                                        'options' => ['prompt' => 'Chọn'],
                                        'pluginOptions' => [
                                            'allowClear' => true,
                                        ],
                                    ],
                                ],
                                [
                                    'attribute' => 'remain',
                                    'value' => function ($data) {
                                        return number_format($data->remain) . 'đ';
                                    },
                                ],
                                [
                                    'class' => 'kartik\grid\FormulaColumn',
                                    'header' => 'Tổng tiền',
                                    'vAlign' => 'middle',
                                    'value' => function ($model, $key, $index, $widget) {
                                        return number_format($model->grandTotal) . "₫";
                                    },
                                    'headerOptions' => ['class' => 'kartik-sheet-style'],
                                    'hAlign' => 'right',
                                    'width' => '7%',
                                    'mergeHeader' => true,
                                    'footer' => true,
                                ],
                                [
                                    'attribute' => 'edited',
                                    'header' => 'Chỉnh sửa',
                                    'format' => 'html',
                                    'filterType' => GridView::FILTER_SELECT2,
                                    'filter' => [
                                        2 => 'Không',
                                        3 => 'Có',
                                    ],
                                    'filterWidgetOptions' => [
                                        'options' => ['prompt' => 'Chọn'],
                                        'pluginOptions' => [
                                            'allowClear' => true,
                                        ],
                                    ],
                                    'value' => function (Order $model, $key, $index, $widget) {
                                        if ($model->isEdited()) {
                                            return '<b style="color:red;">Có</b>';
                                        }
                                        return '<b style="color:#0b3d80;">Không</b>';
                                    },
                                    'headerOptions' => ['class' => 'kartik-sheet-style'],
                                    'hAlign' => 'right',
                                    'width' => '7%',
                                ],
                                // 'customer_phone',
                                // 'customer_address',
                                // 'discount_type',
                                //'total',
                                // 'note:ntext',
                                // 'status',
                                // 'updated_date',
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
                            ],
                        ]); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>

        $(document).ready(function () {
            var toggle = '<?= (isset($_GET['_tog1149016d']) && $_GET['_tog1149016d'] != '') ? $_GET['_tog1149016d'] : 0 ?>';
            if ($('#ordersearch-event_id').val() != '') {
                $('.button-error').hide();
            } else {
                $('.button-error').show();
                $('#w0-togdata-page').hide();
                if (toggle != 0) {
                    window.location.replace("<?= Url::to(['order/index'])?>");
                }
            }
            $(document).on('click', '.button-error', function () {
                alert('Hãy chọn một sự kiện');
                return false;
            })
        });
    </script>
