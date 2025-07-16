<?php
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Change Password: ' . $model->username;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->username, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Change Password';
?>

<div class="user-update">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="user-form">
        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'password')->passwordInput()->hint('Enter your new password. Leave blank to keep current password.') ?>
        <?= $form->field($model, 'confirm_password')->passwordInput()->label('Confirm Password') ?>

        <div class="form-group">
            <?= Html::submitButton('Change Password', ['class' => 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>