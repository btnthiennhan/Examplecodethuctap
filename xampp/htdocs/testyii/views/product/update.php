<?php
use yii\helpers\Html;
$this->title = 'Cập nhật: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Danh sách', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Cập nhật';
?>

<h1 class="h4 fw-bold mb-4"><?= Html::encode($this->title) ?></h1>
<?= $this->render('_form', ['model' => $model]) ?>
