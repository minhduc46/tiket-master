<?php
/**
 * Created by PhpStorm.
 * Author: Phuong
 * Email: notteen@gmail.com
 * Date: 09/02/2017
 * Time: 3:43 CH
 */
use app\models\Order;
use kartik\date\DatePicker;
use kartik\form\ActiveForm;
use kartik\widgets\DateTimePicker;
use kartik\widgets\Select2;
use yii\helpers\Html;

$form = ActiveForm::begin();
if($type == 1) {
	$this->title = 'Gia hạn';
} else {
	$this->title = 'Hủy nợ';
}
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="event-create">

	<div class="page-header position-relative">
		<?php if($type == 1):?>
		<h1>
			Gia hạn vé
		</h1>
		<?php else:?>
		<h1>
			Xóa nợ vé
		</h1>
		<?php endif;?>
	</div>
	<?php if($type == 1): ?>
		<div class="col-xs-12">
			<div class="col-xs-4">
				<?= $form->field($model, 'adjourn_date')->widget(DatePicker::className(), [
					'type'          => DatePicker::TYPE_INLINE,
					'pluginOptions' => [
						'autoclose' => true,
						'format'    => 'yyyy-mm-dd',
					],
				]); ?>
			</div>
		</div>
	<?php endif; ?>
	<?php if($type==2): ?>

		<div class="col-xs-12">
			<div class="col-xs-4">
				<?= $form->field($model, 'remain')->textInput(['id' => 'remain']) ?>
			</div>
		</div>
	<?php endif; ?>
	<div class="col-xs-12">
		<div class="col-xs-4">
			<?= $form->field($model, 'status')->widget(Select2::className(), [
				'data'          => Order::getArrStatus(),
				'options'       => ['placeholder' => 'Chọn trạng thái ...'],
				'pluginOptions' => [
					'allowClear' => true,
				],
			]) ?>
		</div>

	</div>
	<div class="form-group col-xs-12">
		<?php if($type==2): ?>
		<div class="col-xs-2">
			<?= Html::button('Xóa nợ', [
				'class' => 'btn btn-danger',
				'id'    => 'clear-remain',
			]) ?>
		</div>
		<?php endif;?>
		<div class="col-xs-2">    <?= Html::submitButton($model->isNewRecord ? 'Tạo mới' : 'Cập nhật', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?></div>

	</div>
	<?php ActiveForm::end() ?>
</div>
<script>
	var remain = 0;
	$(document).ready(function() {
		$('#clear-remain').click(function() {
			console.log($(this).val());
			var remainText = $('#remain');
			if(remain == 0) {
				remain = remainText.val();
				remainText.val(0);
			}
			else {
				remainText.val(remain);
				remain = 0;
			}
			if(remain != 0) {
				$(this).text('Hủy');
			} else {
				$(this).text('Xóa nợ');
			}
		})
	})
</script>