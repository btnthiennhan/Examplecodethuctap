<?php
use yii\helpers\Html;
$this->title = 'Cảm ơn';
?>

<h1>Cảm ơn bạn đã đặt hàng!</h1>
<p>Đơn hàng ID: <?= Html::encode($order['id']) ?></p>
<p>Tổng tiền: <?= Yii::$app->formatter->asCurrency($total) ?></p>
<?php if (isset($message)): ?>
    <p><?= Html::encode($message) ?></p>
<?php endif; ?>