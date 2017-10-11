<?php
use app\controllers\MapController;
use app\models\Event;
use app\models\Order;
use app\models\User;
use kartik\editable\Editable;
use kartik\grid\GridView;
use navatech\role\helpers\RoleChecker;
use yii\bootstrap\Html;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
?>
<?php if ($type==1&&RoleChecker::isAuth(MapController::className(),'view')): ?>
	<div class="modal fade" id="Modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="myModalLabel">Đơn hàng quá hạn</h4>
				</div>
				<div class="modal-body">

					<?= GridView::widget([
						'dataProvider' => $dataProvider_chair_time_out,
						'bordered' => true,
						'responsive' => true,
						'toolbar' => false,
						'export' => false,
						'rowOptions' => [
							'style' => [
								'text-align' => 'center',
							],
						],
						'columns' => [

							[
								'attribute' => 'booked_date',
								'format' => [
									'date',
									'php:d-m-Y',
								],
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
							// 'customer_phone',
							// 'customer_address',
							// 'discount_type',
							//'total',
							// 'note:ntext',
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
							//
							// 'booked_date',
						],
						'panel' => [
							'heading' => '<i><i class="glyphicon glyphicon-leaf"></i>' . Yii::t('app', 'Danh sách đơn hàng') . '</i>',
							'footer' => false,
						],
					]); ?>

				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
					<a href="<?= Url::to([
						'/order/chair-time-out',
						'id' => $event,
					]) ?>">
						<button type="button" class="btn btn-primary">Xem chi tiết</button>
					</a>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="Modal1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="myModalLabel">Đơn hàng còn nợ</h4>
				</div>
				<div class="modal-body">

					<?= GridView::widget([
						'dataProvider' => $dataProvider,
						'export' => false,
						'bordered' => true,
						'toolbar' => false,
						'responsive' => true,
						'rowOptions' => [
							'style' => [
								'text-align' => 'center',
							],
						],
						'columns' => [

							[
								'attribute' => 'booked_date',
								'format' => [
									'date',
									'php:d-m-Y',
								],
							],
							[
								'attribute' => 'number',
								'value' => function (Order $data) {
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
							// 'customer_phone',
							// 'customer_address',
							// 'discount_type',
							//'total',
							// 'note:ntext',
							// 'status',
							// 'booked_date',
						],
						'panel' => [
							'heading' => '<i><i class="glyphicon glyphicon-leaf"></i>' . Yii::t('app', 'Danh sách đơn hàng') . '</i>',
							'footer' => false,
						],
					]); ?>

				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
					<a href="<?= Url::to([
						'/order/owe',
						'id' => $event,
					]) ?>">
						<button type="button" class="btn btn-primary">Xem chi tiết</button>
					</a>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="Modal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="myModalLabel">Đơn hàng chưa xuất kho</h4>
				</div>
				<div class="modal-body">

					<?= GridView::widget([
						'dataProvider' => $dataProvider1,
						'bordered' => true,
						'responsive' => true,
						'toolbar' => false,
						'export' => false,
						'rowOptions' => [
							'style' => [
								'text-align' => 'center',
							],
						],
						'columns' => [

							[
								'attribute' => 'booked_date',
								'format' => [
									'date',
									'php:d-m-Y',
								],
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
							// 'customer_phone',
							// 'customer_address',
							// 'discount_type',
							//'total',
							// 'note:ntext',
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
							//
							// 'booked_date',
						],
						'panel' => [
							'heading' => '<i><i class="glyphicon glyphicon-leaf"></i>' . Yii::t('app', 'Danh sách đơn hàng') . '</i>',
							'footer' => false,
						],
					]); ?>

				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
					<a href="<?= Url::to([
						'/order/xk',
						'id' => $event,
					]) ?>">
						<button type="button" class="btn btn-primary">Xem chi tiết</button>
					</a>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="Modal3" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="myModalLabel">Tổng nợ các đơn hàng</h4>
				</div>
				<div class="modal-body">

					<?= GridView::widget([
						'dataProvider' => $provider,
						'bordered' => true,
						'responsive' => true,
						'export' => false,
						'toolbar' => false,
						'rowOptions' => [
							'style' => [
								'text-align' => 'center',
							],
						],
						'columns' => [
							[
								'attribute' => 'start_date',
								'format' => [
									'date',
									'php:d-m-Y',
								],
								'header' => 'Ngày bắt đầu',
							],
							[
								'attribute' => 'end_date',
								'format' => [
									'date',
									'php:d-m-Y',
								],
								'header' => 'Ngày kết thúc',
							],
							[
								'attribute' => 'number',
								'header' => 'Tổng tiền nợ',
							],
							// 'customer_phone',
							// 'customer_address',
							// 'discount_type',
							//'total',
							// 'note:ntext',
							// 'status',
							// 'booked_date',
						],
						'panel' => [
							'heading' => '<i><i class="glyphicon glyphicon-leaf"></i>' . Yii::t('app', 'Danh sách đơn hàng') . '</i>',
							'footer' => false,
						],
					]); ?>

				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
					<a href="<?= Url::to([
						'/order/owe',
						'id' => $event,
					]) ?>">
						<button type="button" class="btn btn-primary">Xem chi tiết</button>
					</a>
				</div>
			</div>
		</div>
	</div>

<?php endif; ?>
<script>
	$(document).on("click", "#timeOut", function () {
		$(this).toggleClass("open");
		$("#Modal").modal();
		console.log($(this).attr('about'));
		return false;
	});
	$(document).on("click", "#owe", function () {
		$("#Modal1").modal();
		return false;
	});
	$(document).on("click", "#xk", function () {
		$("#Modal2").modal();
		return false;
	});
	$(document).on("click", "#SumOwe", function () {
		$("#Modal3").modal();
		return false;
	});
</script>