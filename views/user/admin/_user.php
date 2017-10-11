<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

/**
 * @var yii\widgets\ActiveForm $form
 * @var dektrium\user\models\User $user
 */
use app\models\Branch;
use navatech\role\models\Role;
use yii\helpers\ArrayHelper;

?>

<?= $form->field($user, 'email')->textInput(['maxlength' => 255]) ?>
<?= $form->field($user, 'username')->textInput(['maxlength' => 255]) ?>
<?= $form->field($user, 'password')->passwordInput() ?>
<?= $form->field($user, 'role_id', ['labelOptions' => ['class' => 'control-label col-sm-3']])
         ->dropDownList(ArrayHelper::map(Role::find()->all(), 'id', 'name')) ?>
<?= $form->field($user, 'branch_id', ['labelOptions' => ['class' => 'control-label col-sm-3']])
         ->dropDownList(ArrayHelper::map(Branch::find()->all(), 'id', 'name')) ?>

