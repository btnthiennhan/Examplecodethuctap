<?php
use yii\helpers\Html;
?>
<h1>Thanh toán qua MoMo</h1>
<p>Quét mã QR dưới đây bằng ứng dụng MoMo Developer để thanh toán:</p>
<?php if ($qr_code_url): ?>
    <img src="<?= Html::encode($qr_code_url) ?>" alt="MoMo QR Code" style="max-width: 300px;">
    <p>Hoặc <a href="<?= Html::encode($pay_url) ?>" target="_blank">nhấn vào đây</a> để thanh toán trực tiếp.</p>
<?php else: ?>
    <p>Không thể tải mã QR. Vui lòng thử lại.</p>
<?php endif; ?>
<p>Thông báo: <?= Html::encode($message) ?></p>