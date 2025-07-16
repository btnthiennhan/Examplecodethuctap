
<?php
use yii\helpers\Html;
$this->title = 'Thêm mới';
$this->params['breadcrumbs'][] = ['label' => 'Danh sách', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<h1 class="h4 fw-bold mb-4"><?= Html::encode($this->title) ?></h1>
<?= $this->render('_form', ['model' => $model]) ?>

<style>
.card-title {
    font-size: 1.1rem;
}
.card-text {
    font-size: 0.875rem;
}
.btn i {
    margin-right: 4px;
}
</style>