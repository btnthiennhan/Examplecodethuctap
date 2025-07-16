<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
?>

<div class="card shadow p-4 rounded-4 border-0">
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 4]) ?>

    <div class="form-group mt-3">
        <?= Html::submitButton('<i class="bi bi-save"></i> Lưu', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Huỷ', ['index'], ['class' => 'btn btn-secondary ms-2']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
