<?php
use app\models\Event;
use app\models\Order;
use app\models\User;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title                   = 'Ghế quá hạn';
$this->params['breadcrumbs'][] = $this->title;
$bundle                        = \app\assets\AppAsset::register($this);
?>
<link rel="stylesheet" href="<?= $bundle->baseUrl ?>/web/css/order.css">

</link>
<div class="order-index">
	<div class="page-content">
		<div class="row-fluid">
			<div class="span12">
				<div class="row-fluid">

					<?php if($type == 1): ?>
						<h3 class="header smaller lighter blue">Các hóa đơn bán vé quá hạn chương trình: <?= $event->name ?></h3>

                       <?php echo GridView::widget([
                            'dataProvider' => $dataProvider1,
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
                                '{export}',
                                '{toggleData}',
                            ],
                            'columns'      => [
                                ['class' => 'yii\grid\SerialColumn'],
                                [
                                    'class' => 'kartik\grid\EditableColumn',
                                    'attribute' => 'adjourn_date',
                                    'pageSummary' => true,
                                    'footer' => true,
                                    'format' => ['date', 'php:d-m-Y'],
                                    'editableOptions' => function ($model, $key, $index, $widget) {
                                        return [
                                            'header'=>'gia hạn ngày',
                                            'size'=>'md',
                                            'inputType'=>\kartik\editable\Editable::INPUT_WIDGET,
                                            'widgetClass'=> 'kartik\datecontrol\DateControl',
                                            'options'=>[
                                                'type'=>\kartik\datecontrol\DateControl::FORMAT_DATE,
                                                'displayFormat'=>'dd.MM.yyyy',
                                                'saveFormat'=>'php:Y-m-d',
                                                'options'=>[
                                                    'pluginOptions'=>[
                                                        'autoclose'=>true
                                                    ]
                                                ]
                                            ],
                                            'formOptions' => [
                                                'action' => [
                                                    '/edit-order/adjourn',
                                                    'number' => $model->number,
                                                    'event_id' => $model->event_id,
                                                ],
                                            ],

                                        ];
                                    },
                                ],
                                [
                                    'attribute'           => 'event_id',
                                    'filterType'          => GridView::FILTER_SELECT2,
                                    'filter'              => ArrayHelper::map(Event::find()->asArray()->all(), 'id', 'name'),
                                    'filterWidgetOptions' => [
                                        'options'       => ['placeholder' => 'Chọn sự kiện...'],
                                        'pluginOptions' => [
                                            'allowClear' => true,
                                        ],
                                    ],
                                    'value'               => function ($data) {
                                        return $data->getTextEvent($data->event_id);
                                    },
                                ],
                                [
                                    'class'         => 'kartik\grid\FormulaColumn',
                                    'header'        => 'Đơn vị phân phối',
                                    'vAlign'        => 'middle',
                                    'value'         => function ($model, $key, $index, $widget) {
                                        return $model->getTextBrain($model->user_id);
                                    },
                                    'headerOptions' => ['class' => 'kartik-sheet-style'],
                                    'hAlign'        => 'right',
                                    'width'         => '5%',
                                    'mergeHeader'   => true,
                                    'footer'        => true,
                                ],
                                [
                                    'attribute'           => 'user_id',
                                    'filterType'          => GridView::FILTER_SELECT2,
                                    'filter'              => ArrayHelper::map(User::find()->asArray()->all(), 'id', 'username'),
                                    'filterWidgetOptions' => [
                                        'pluginOptions' => [
                                            'allowClear' => true,
                                        ],
                                    ],
                                    'value'               => function ($data) {
                                        return $data->getTextUser($data->user_id);
                                    },
                                ],
                                [
                                    'attribute' => 'number',
                                    'value'     => function( $data) {
                                        return str_pad($data->number, 7, '0', STR_PAD_LEFT);
                                    },
                                ],
                                'customer_name',
                                [
                                    'class'         => 'kartik\grid\FormulaColumn',
                                    'header'        => "<div class='order-detail header' style='text-align: center'>Chi tiết đơn hàng</div><div class='order-detail col-xs-12'><span class='col-xs-2'>Loại vé</span><span class='col-xs-2'>SL</span><span class='col-xs-5'>Vị trí</span><span class='col-xs-3'>Thành tiền</span></div>",
                                    'format'        => 'html',
                                    'value'         => function ($model, $key, $index, $widget) {
                                        return $model->getDescription();
                                    },
                                    'headerOptions' => ['class' => 'kartik-sheet-style'],
                                    'hAlign'        => 'right',
                                    'width'         => '40%',
                                    'mergeHeader'   => true,
                                    'footer'        => true,
                                ],
                                [
                                    'class'         => 'kartik\grid\FormulaColumn',
                                    'header'        => 'Tiền vé',
                                    'vAlign'        => 'middle',
                                    'value'         => function ($model, $key, $index, $widget) {
                                        return number_format($model->subTotal);
                                    },
                                    'headerOptions' => ['class' => 'kartik-sheet-style'],
                                    'hAlign'        => 'right',
                                    'width'         => '7%',
                                    'mergeHeader'   => true,
                                    'mergeHeader'   => true,
                                    'footer'        => true,
                                ],
                                [
                                    'attribute' => 'discount',
                                    'value'     => function ($model) {
                                        return number_format($model->discount) . $model->getDiscountType();
                                    },
                                ],
                                [
                                    'class'         => 'kartik\grid\FormulaColumn',
                                    'header'        => 'Tổng tiền',
                                    'vAlign'        => 'middle',
                                    'value'         => function ($model, $key, $index, $widget) {
                                        return $model->grandTotal . "₫";
                                    },
                                    'headerOptions' => ['class' => 'kartik-sheet-style'],
                                    'hAlign'        => 'right',
                                    'width'         => '7%',
                                    'mergeHeader'   => true,
                                    'footer'        => true,
                                ],

                                // 'customer_phone',
                                // 'customer_address',
                                // 'discount_type',
                                //'total',
                                // 'remain',
                                // 'note:ntext',
                                // 'status',
                                // 'updated_date',
                                [
                                    'class'    => 'yii\grid\ActionColumn',
                                    'template' => '{delete}',
                                    'buttons'  => [
                                        //view button
                                        'delete' => function ($url, $model) {
                                            return Html::a('<span class="glyphicon glyphicon-trash"></span>', Url::to([
                                                'delete-order',
                                                'number' => $model->number,
	                                            'event_id'=>$model->event_id,
	                                            'type'    =>1
                                            ]), [
                                                'title'        => Yii::t('app', 'Xóa đơn hàng'),
                                                'data-method'  => 'post',
                                                'data-confirm' => "Bạn có chắc là sẽ xóa mục này không?",
                                            ]);
                                        },
                                    ],
                                ],
                            ],
                            'panel'        => [
                                'heading' => '<i><i class="glyphicon glyphicon-leaf"></i>' . Yii::t('app', 'Danh sách đơn hàng') . '</i>',
                                'after'   => Html::a('<i class="glyphicon glyphicon-repeat"></i>' . Yii::t("app", " Reset"), ['index'], ['class' => 'btn btn-info']),
                                'footer'  => false,
                            ],
                        ]);
                       ?>
					<?php elseif($type == 2): ?>
						<h3 class="header smaller lighter blue">Các hóa đơn bán vé nợ chương trình: <?= $event->name ?></h3>

						<?= GridView::widget([
						        'id'=>'gridview',
							'dataProvider' => $dataProvider,
							'bordered'     => true,
							'responsive'   => true,
							'rowOptions'   => [
								'style' => [
									'text-align' => 'center',
								],
							],
							'export'       => ['target' => GridView::TARGET_SELF],
							'toolbar'      => [
								'{export}',
								'{toggleData}',
							],
							'columns'      => [
								['class' => 'yii\grid\SerialColumn'],
								[
									'attribute'           => 'updated_date',
									'filterType'          => GridView::FILTER_DATE,
									'filterWidgetOptions' => [
										'pluginOptions' => [
											'autoclose' => true,
											'format'    => 'dd-mm-yyyy',
										],
									],
									'format'              => [
										'date',
										'php:d-m-Y',
									],
								],
								[
									'attribute'           => 'event_id',
									'filterType'          => GridView::FILTER_SELECT2,
									'filter'              => ArrayHelper::map(Event::find()->asArray()->all(), 'id', 'name'),
									'filterWidgetOptions' => [
										'options'       => ['placeholder' => 'Chọn sự kiện...'],
										'pluginOptions' => [
											'allowClear' => true,
										],
									],
									'value'               => function ($data) {
										return $data->getTextEvent($data->event_id);
									},
								],
								[
									'class'         => 'kartik\grid\FormulaColumn',
									'header'        => 'Đơn vị phân phối',
									'vAlign'        => 'middle',
									'value'         => function ($model, $key, $index, $widget) {
										return $model->getTextBrain($model->user_id);
									},
									'headerOptions' => ['class' => 'kartik-sheet-style'],
									'hAlign'        => 'right',
									'width'         => '5%',
									'mergeHeader'   => true,
									'footer'        => true,
								],
								[
									'attribute'           => 'user_id',
									'filterType'          => GridView::FILTER_SELECT2,
									'filter'              => ArrayHelper::map(User::find()->asArray()->all(), 'id', 'username'),
									'filterWidgetOptions' => [
										'pluginOptions' => [
											'allowClear' => true,
										],
									],
									'value'               => function ($data) {
										return $data->getTextUser($data->user_id);
									},
								],
                                [
                                    'attribute' => 'number',
                                    'value'     => function($data) {
                                        return str_pad($data->number, 7, '0', STR_PAD_LEFT);
                                    },
                                ],
								'customer_name',
                                [
                                    'class' => 'kartik\grid\EditableColumn',
                                    'attribute' => 'remain',
                                    'pageSummary' => true,
                                    'footer' => true,
                                    'value' => function ($data) {
                                        return number_format($data->remain) . 'đ';
                                    },
                                    'editableOptions' => function ($model, $key, $index, $widget) {
                                        return [
                                            'header' => 'Nợ',
                                            'size' => 'md',

                                            'inputType' => \kartik\editable\Editable::INPUT_MONEY,
                                            'options'=>[
	                                            'pluginOptions' => [
		                                            'prefix' => '$',
	                                            ],
                                            ],

                                            'formOptions' => [
                                                'action' => [
                                                    '/edit-order/remain',
                                                    'id' => $model->id,
                                                    'number' => $model->number,
                                                    'event_id' => $model->event_id,
                                                ],

                                            ],

                                        ];
                                    },
                                ],
								[
									'class'         => 'kartik\grid\FormulaColumn',
									'header'        => "<div class='order-detail header' style='text-align: center'>Chi tiết đơn hàng</div><div class='order-detail col-xs-12'><span class='col-xs-2'>Loại vé</span><span class='col-xs-2'>SL</span><span class='col-xs-5'>Vị trí</span><span class='col-xs-3'>Thành tiền</span></div>",
									'format'        => 'html',
									'value'         => function ($model, $key, $index, $widget) {
										return $model->getDescription();
									},
									'headerOptions' => ['class' => 'kartik-sheet-style'],
									'hAlign'        => 'right',
									'width'         => '40%',
									'mergeHeader'   => true,
									'footer'        => true,
								],
								[
									'class'         => 'kartik\grid\FormulaColumn',
									'header'        => 'Tiền vé',
									'vAlign'        => 'middle',
									'value'         => function ($model, $key, $index, $widget) {
										return number_format($model->subTotal);
									},
									'headerOptions' => ['class' => 'kartik-sheet-style'],
									'hAlign'        => 'right',
									'width'         => '7%',
									'mergeHeader'   => true,
									'footer'        => true,
								],
								[
									'attribute' => 'discount',
									'value'     => function ($model) {
										return number_format($model->discount) . $model->getDiscountType();
									},
								],
								[
									'class'         => 'kartik\grid\FormulaColumn',
									'header'        => 'Tổng tiền',
									'vAlign'        => 'middle',
									'value'         => function ($model, $key, $index, $widget) {
										return $model->grandTotal . "₫";
									},
									'headerOptions' => ['class' => 'kartik-sheet-style'],
									'hAlign'        => 'right',
									'width'         => '7%',
									'mergeHeader'   => true,
									'footer'        => true,
								],
								[
									'class'         => 'kartik\grid\FormulaColumn',
									'header'        => 'Chỉnh sửa',
									'format'        => 'html',
									'vAlign'        => 'middle',
									'value'         => function ($model, $key, $index, $widget) {
										if($model->isEdited()) {
											return '<b style="color:red;">Có</b>';
										}
										return '<b style="color:#0b3d80;">Không</b>';
									},
									'headerOptions' => ['class' => 'kartik-sheet-style'],
									'hAlign'        => 'right',
									'width'         => '7%',
									'mergeHeader'   => true,
									'footer'        => true,
								],
								// 'customer_phone',
								// 'customer_address',
								// 'discount_type',
								//'total',
								[
										'attribute'=>'remain',
										'format'=>['decimal', 2],
										'mergeHeader'=>true,
										'pageSummary'=>true,
										'footer'=>true
								],
								// 'note:ntext',
								// 'status',
								// 'updated_date',
								[
									'class'    => 'yii\grid\ActionColumn',
									'template' => '{delete}',
									'buttons'  => [
										//view button

										'delete' => function ($url, $model) {
											return Html::a('<span class="glyphicon glyphicon-trash"></span>', Url::to([
												'delete-order',
												'number' => $model->number,
												'event_id'=>$model->event_id,
                                                'type'=>3
											]), [
												'title'        => Yii::t('app', 'Xóa đơn hàng'),
												'data-method'  => 'post',
												'data-confirm' => "Bạn có chắc là sẽ xóa mục này không?",
											]);
										},
									],
								],
							],
							'panel'        => [
								'heading' => '<i><i class="glyphicon glyphicon-leaf"></i>' . Yii::t('app', 'Danh sách đơn hàng') . '</i>',
								'after'   => Html::a('<i class="glyphicon glyphicon-repeat"></i>' . Yii::t("app", " Reset"), ['index'], ['class' => 'btn btn-info']),
								'footer'  => false,
							],
						]); ?>
					<?php else: ?>
						<h3 class="header smaller lighter blue">Các hóa đơn bán vé chưa xuất kho chương trình: <?= $event->name ?></h3>

						<?= GridView::widget([
							'dataProvider' => $dataProvider,
                            'id'=>'gridview',
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
								'{export}',
								'{toggleData}',
							],
							'columns'      => [
								['class' => 'yii\grid\SerialColumn'],
								[
									'attribute'           => 'updated_date',
									'filterType'          => GridView::FILTER_DATE,
									'filterWidgetOptions' => [
										'pluginOptions' => [
											'autoclose' => true,
											'format'    => 'dd-mm-yyyy',
										],
									],
									'format'              => [
										'date',
										'php:d-m-Y',
									],
								],
								[
									'attribute'           => 'event_id',
									'filterType'          => GridView::FILTER_SELECT2,
									'filter'              => ArrayHelper::map(Event::find()->asArray()->all(), 'id', 'name'),
									'filterWidgetOptions' => [
										'options'       => ['placeholder' => 'Chọn sự kiện...'],
										'pluginOptions' => [
											'allowClear' => true,
										],
									],
									'value'               => function ($data) {
										return $data->getTextEvent($data->event_id);
									},
								],
								[
									'class'         => 'kartik\grid\FormulaColumn',
									'header'        => 'Đơn vị phân phối',
									'vAlign'        => 'middle',
									'value'         => function ($model, $key, $index, $widget) {
										return $model->getTextBrain($model->user_id);
									},
									'headerOptions' => ['class' => 'kartik-sheet-style'],
									'hAlign'        => 'right',
									'width'         => '5%',
									'mergeHeader'   => true,
									'footer'        => true,
								],
								[
									'attribute'           => 'user_id',
									'filterType'          => GridView::FILTER_SELECT2,
									'filter'              => ArrayHelper::map(User::find()->asArray()->all(), 'id', 'username'),
									'filterWidgetOptions' => [
										'pluginOptions' => [
											'allowClear' => true,
										],
									],
									'value'               => function ($data) {
										return $data->getTextUser($data->user_id);
									},
								],
                                [
                                    'attribute' => 'number',
                                    'value'     => function(Order $data) {
                                        return str_pad($data->number, 7, '0', STR_PAD_LEFT);
                                    },
                                ],
								'customer_name',
                                [
                                    'class' => 'kartik\grid\EditableColumn',
                                    'attribute' => 'status',
                                    'pageSummary' => true,
                                    'footer' => true,
                                    'value' => function ($data) {
                                        return Order::getArrStatus()[$data->status];
                                    },
                                    'editableOptions' => function ($model, $key, $index, $widget) {
                                        return [
                                            'header' => 'Trạng thái',
                                            'size' => 'md',
                                            'inputType' => \kartik\editable\Editable::INPUT_SELECT2,
                                            'options' => [
                                                'data' => Order::getArrStatus()
                                            ],
                                            'formOptions' => [
                                                'action' => [
                                                    '/edit-order/status',
                                                    'id' => $model->id,
                                                    'number' => $model->number,
                                                    'event_id' => $model->event_id,
                                                ],
                                            ],
                                        ];
                                    },
                                ],
								[
									'class'         => 'kartik\grid\FormulaColumn',
									'header'        => "<div class='order-detail header' style='text-align: center'>Chi tiết đơn hàng</div><div class='order-detail col-xs-12'><span class='col-xs-2'>Loại vé</span><span class='col-xs-2'>SL</span><span class='col-xs-5'>Vị trí</span><span class='col-xs-3'>Thành tiền</span></div>",
									'format'        => 'html',
									'value'         => function ($model, $key, $index, $widget) {
										return $model->getDescription();
									},
									'headerOptions' => ['class' => 'kartik-sheet-style'],
									'hAlign'        => 'right',
									'width'         => '40%',
									'mergeHeader'   => true,
									'footer'        => true,
								],
								[
									'class'         => 'kartik\grid\FormulaColumn',
									'header'        => 'Tiền vé',
									'vAlign'        => 'middle',
									'value'         => function ($model, $key, $index, $widget) {
										return number_format($model->subTotal);
									},
									'headerOptions' => ['class' => 'kartik-sheet-style'],
									'hAlign'        => 'right',
									'width'         => '7%',
									'mergeHeader'   => true,
									'footer'        => true,
								],
								[
									'attribute' => 'discount',
									'value'     => function ($model) {
										return number_format($model->discount) . $model->getDiscountType();
									},
								],
								[
									'class'         => 'kartik\grid\FormulaColumn',
									'header'        => 'Tổng tiền',
									'vAlign'        => 'middle',
									'value'         => function ($model, $key, $index, $widget) {
										return $model->grandTotal . "₫";
									},
									'headerOptions' => ['class' => 'kartik-sheet-style'],
									'hAlign'        => 'right',
									'width'         => '7%',
									'mergeHeader'   => true,
									'footer'        => true,
								],
								[
									'class'         => 'kartik\grid\FormulaColumn',
									'header'        => 'Chỉnh sửa',
									'format'        => 'html',
									'vAlign'        => 'middle',
									'value'         => function ($model, $key, $index, $widget) {
										if($model->isEdited()) {
											return '<b style="color:red;">Có</b>';
										}
										return '<b style="color:#0b3d80;">Không</b>';
									},
									'headerOptions' => ['class' => 'kartik-sheet-style'],
									'hAlign'        => 'right',
									'width'         => '7%',
									'mergeHeader'   => true,
									'footer'        => true,
								],
								// 'customer_phone',
								// 'customer_address',
								// 'discount_type',
								//'total',
								[
										'attribute'=>'remain',
										'value'=>function($data)
										{
											return number_format($data->remain).'₫';
										}
								],
								// 'note:ntext',
								// 'status',
								// 'updated_date',
								[
									'class'    => 'yii\grid\ActionColumn',
									'template' => '{delete}',
									'buttons'  => [
										//view button

										'delete' => function ($url, $model) {
											return Html::a('<span class="glyphicon glyphicon-trash"></span>', Url::to([
												'delete-order',
												'number' => $model->number,
												'event_id'=>$model->event_id,
                                                'type'=>2
											]), [
												'title'        => Yii::t('app', 'Xóa đơn hàng'),
												'data-method'  => 'post',
												'data-confirm' => "Bạn có chắc là sẽ xóa mục này không?",
											]);
										},
									],
								],
							],
							'panel'        => [
								'heading' => '<i><i class="glyphicon glyphicon-leaf"></i>' . Yii::t('app', 'Danh sách đơn hàng') . '</i>',
								'after'   => Html::a('<i class="glyphicon glyphicon-repeat"></i>' . Yii::t("app", " Reset"), ['index'], ['class' => 'btn btn-info']),
								'footer'  => false,
							],
						]); ?>
					<?php endif; ?>

					<?php // echo $this->render('_search', ['model' => $searchModel]); ?>

				</div>
			</div>
		</div>
	</div>
</div>
<script>
    $(document).on('click','.kv-editable-submit',function () {
        $.pjax.reload({container: "#gridview"});
    })
</script>