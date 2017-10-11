<?php
use app\models\Price;
use app\models\User;
use kartik\color\ColorInput;
use kartik\datecontrol\DateControl;
use kartik\field\FieldRange;
use kartik\form\ActiveForm;
use kartik\widgets\Select2;
use kartik\widgets\SwitchInput;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Event */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="row-fluid" id="crop-image">
	<div class="span12">
		<?php $form = ActiveForm::begin(); ?>
		<div class="row-fluid">
			<div class="col-sm-6">
				<?php echo $form->errorSummary($model); ?>
				<h4 class="header smaller lighter blue">Thông tin chung</h4>

				<?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
				<?= Html::error($model, 'name'); ?>
				<div class="form-group">
					<label class="control-label"><?=Html::activeLabel($model,'start_date')?></label>
					<?= FieldRange::widget([
						'form'           => $form,
						'model'          => $model,
						'label'          => '',
						'attribute1'     => 'start_date',
						'attribute2'     => 'end_date',
						'type'           => FieldRange::INPUT_WIDGET,
						'widgetClass'    => DateControl::classname(),
						'widgetOptions1' => [
							'displayFormat' => 'php:d-m-Y',
							'saveFormat'    => 'php:Y-m-d',
							'options'       => [
								'pluginOptions' => ['autoclose' => true,],
							],
						],
						'widgetOptions2' => [
							'displayFormat' => 'php:d-m-Y',
							'saveFormat'    => 'php:Y-m-d',
							'options'       => [
								'pluginOptions' => ['autoclose' => true,],
							],
						],
					]); ?>
					<?php echo Html::error($model, 'start_date').' '.Html::error($model, 'date_end'); ?>
				</div>

				<?= $form->field($user, 'user_id')->widget(Select2::className(), [
					'data'          => ArrayHelper::map(User::find()->asArray()->all(), 'id', 'username'),
					'maintainOrder' => true,
					'options'       => [
						'placeholder' => 'Chọn nhân viên...',
						'multiple'    => true,
					],
					'pluginOptions' => [
						'tags'               => true,
						'maximumInputLength' => 10,
					],
				])->label('Nhân viên *') ?>

				<?= $form->field($model, 'status')->widget(SwitchInput::className(), [
					'pluginOptions' => [
						'size'     => 'large',
						'onColor'  => 'success',
						'offColor' => 'danger',
						'onText'   => 'Có',
						'offText'  => 'Không',
						'value'    => 1,
					],
				])->label('Trạng thái*') ?>

			</div>
		</div>

		<div class="row-fluid" style="margin-top: 30px">
			<div class="col-sm-6">
				<h4 class="header smaller lighter blue">Cài đặt giá</h4>
				<?php foreach($prices as $key => $price):
					if($price->isNewRecord) {
						$colors       = array_values(Price::getColor());
						$price->color = $colors[$key];
					}
					?>
					<div class="form-group">
						<label class="col-sm-12 control-label">Giá và màu khu vực <?php echo $key + 1; ?></label>
						<div class="col-sm-8">
							<?php echo $form->field($price, 'price')->textInput([
								'class' => 'span2 money-mask',
								'name'  => 'Price[' . $key . '][price]',
								'value' => $price->isNewRecord ? '' : ($price->price == null ? '' : number_format($price->price)),
							])->label(false); ?>

							<?php echo $form->field($price, 'class')->hiddenInput([
								'name'  => 'Price[' . $key . '][class]',
								'value' => $key,
							])->label(false) ?>
						</div>
						<div class="col-sm-4">
							<?php echo ColorInput::widget([
								'name'    => 'Price[' . $key . '][color]',
								'value'   => $price->color,
								'options' => ['readonly' => true],
							]); ?>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
		<div class="form-group col-xs-12">
			<?= Html::submitButton($model->isNewRecord ? 'Thêm' : 'Cập nhật', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
		</div>
		<?php ActiveForm::end(); ?>
	</div>
</div>

<script>
	$(".money-mask").mask('###,###,###,###', {reverse: true});

</script>
