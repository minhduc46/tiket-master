<?php
use yii\widgets\ActiveForm;

$form = ActiveForm::begin();
?>

	<fieldset>
		<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<?= $form->field($model, 'username')->textInput(['class'       => "form-control",
															                                                 'placeholder' => "Tên đăng nhập",
															]) ?>
															<i class="ace-icon fa fa-user"></i>
														</span>
		</label>

		<label class="block clearfix">
														<span class="block input-icon input-icon-right">
																<?= $form->field($model, 'password')->passwordInput(['class'       => "form-control",
																                                                 'placeholder' => "Mậ khẩu",
																]) ?>

															<i class="ace-icon fa fa-lock"></i>
														</span>
		</label>

		<div class="space"></div>

		<div class="clearfix">
			<label class="inline">
				<input type="checkbox" class="ace">
				<span class="lbl">Lưu lượt đăng nhập này</span>
			</label>

			<button type="submit" class="width-40 pull-right btn btn-sm btn-primary">
				<i class="ace-icon fa fa-key"></i>
				<span class="bigger-120">Đăng nhập</span>
			</button>
		</div>

		<div class="space-4"></div>
	</fieldset>
<?php ActiveForm::end()?>