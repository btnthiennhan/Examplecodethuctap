<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\CartItem;

class MomoController extends Controller
{
    public function actionCreatePayment()
    {
        $total = CartItem::getTotal(); // Tổng tiền từ giỏ hàng
        $orderId = time(); // Tạo mã đơn hàng tạm thời
        $orderInfo = "Thanh toán đơn hàng #$orderId";

        /** @var \app\components\MomoService $momo */
        $momo = Yii::$app->momo;

        $result = $momo->createPayment([
            'order_id' => $orderId,
            'amount' => $total,
            'order_info' => $orderInfo,
        ]);

        if ($result['success'] && !empty($result['pay_url'])) {
            return $this->redirect($result['pay_url']);
        }

        Yii::$app->session->setFlash('error', $result['message'] ?? 'Không thể tạo thanh toán MoMo.');
        return $this->redirect(['cart/index']);
    }

    public function actionCallback()
    {
        /** @var \app\components\MomoService $momo */
        $momo = Yii::$app->momo;
        $response = $momo->verifyPayment(Yii::$app->request->post());

        if ($response['success']) {
            // Xử lý đơn hàng thành công ở đây
            Yii::$app->session->setFlash('success', 'Thanh toán thành công!');
        } else {
            Yii::$app->session->setFlash('error', $response['message']);
        }

        return $this->redirect(['cart/index']);
    }
}

