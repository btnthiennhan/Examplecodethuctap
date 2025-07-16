<?php

use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */

?>
<div class="index-tab">
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <?= Html::a(Yii::t('app', 'Create Product'), ['product/create'], ['class' => 'nav-link']) ?>
        </li>
        <li class="nav-item">
            <?= Html::a(Yii::t('app', 'Create Category'), ['category/create'], ['class' => 'nav-link']) ?>
        </li>
        <li class="nav-item">
            <?= Html::a(Yii::t('app', 'View Categories'), ['category/index'], ['class' => 'nav-link']) ?>
        </li>
        <li class="nav-item">
            <?= Html::a(Yii::t('app', 'View Product'), ['product/index'], ['class' => 'nav-link']) ?>
        </li>
    </ul>
</div>
