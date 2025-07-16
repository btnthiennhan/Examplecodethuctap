<?php
use yii\helpers\Html;
use yii\helpers\Url;

/** @var array $items */
/** @var float $total */

$this->title = 'Giỏ hàng';
?>

<h1>Giỏ hàng</h1>

<?php if (empty($items)): ?>
    <p>Giỏ hàng của bạn đang trống.</p>
<?php else: ?>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Sản phẩm</th>
                <th>Giá</th>
                <th>Số lượng</th>
                <th>Thành tiền</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $item): ?>
                <tr>
                    <td><?= Html::encode($item['product']->name) ?></td>
                    <td><?= Yii::$app->formatter->asCurrency($item['product']->price) ?></td>
                    <td>
                        <input type="number" name="quantity" class="quantity-input" 
                               data-product-id="<?= $item['product']->id ?>" 
                               value="<?= $item['quantity'] ?>" min="1" style="width: 60px;" />
                    </td>
                    <td class="subtotal"><?= Yii::$app->formatter->asCurrency($item['subtotal']) ?></td>
                    <td>
                        <?= Html::a('Xoá', ['cart/remove', 'id' => $item['product']->id], [
                            'class' => 'btn btn-danger btn-sm',
                            'data-method' => 'post',
                            'data-confirm' => 'Bạn có chắc muốn xoá sản phẩm này không?'
                        ]) ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h4>Tổng cộng: <span id="total"><?= Yii::$app->formatter->asCurrency($total) ?></span></h4>

    <div class="row">
        <div class="col-md-12">
            <h4>Thông tin giao hàng</h4>
            <?= Html::beginForm(['cart/checkout'], 'post', ['id' => 'checkout-form']) ?>
                <div class="form-group">
                    <label for="shipping_address">Địa chỉ giao hàng</label>
                    <input type="text" name="shipping_address" class="form-control" id="shipping_address" required>
                </div>
                <div class="form-group">
                    <label for="notes">Ghi chú</label>
                    <textarea name="notes" class="form-control" id="notes"></textarea>
                </div>
                <div class="form-group">
                    <label>Phương thức thanh toán</label><br>
                    <input type="radio" name="payment_method" value="cash" checked> Thanh toán tiền mặt<br>
                    <input type="radio" name="payment_method" value="momo"> Thanh toán MoMo
                </div>
                <p>
                    <button type="submit" class="btn btn-success">Thanh toán</button>
                </p>
            <?= Html::endForm() ?>
        </div>
    </div>

    <hr>

    <p>
        <?= Html::a('Xóa giỏ hàng', ['cart/clear'], [
            'class' => 'btn btn-danger',
            'data-method' => 'post',
            'data-confirm' => 'Bạn có chắc muốn xóa toàn bộ giỏ hàng không?'
        ]) ?>
    </p>
<?php endif; ?>

<?php
$this->registerJs("
    $(document).on('change', '.quantity-input', function() {
        var productId = $(this).data('product-id');
        var quantity = $(this).val();

        $.ajax({
            url: '" . Url::to(['cart/update-quantity']) . "',
            type: 'POST',
            data: {
                id: productId,
                quantity: quantity,
                " . Yii::$app->request->csrfParam . ": '" . Yii::$app->request->getCsrfToken() . "'
            },
            success: function(response) {
                if (response.success) {
                    var row = $('tr').find('input[data-product-id=' + productId + ']').closest('tr');
                    row.find('.subtotal').text(response.subtotal);
                    $('#total').text(response.total);
                } else {
                    alert('Cập nhật số lượng thất bại.');
                }
            }
        });
    });

    $('#checkout-form').on('submit', function(e) {
        var paymentMethod = $('input[name=payment_method]:checked').val();
        if (paymentMethod === 'momo') {
            e.preventDefault();
            $(this).attr('action', '" . Url::to(['momo/create-payment']) . "');
            this.submit();
        }
    });
");
?>