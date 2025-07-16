<?php
// controllers/CartController.php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use app\models\CartItem;
use app\models\Order;
use app\models\OrderDetail;
use app\components\MomoService;
use yii\helpers\Url;
use GuzzleHttp\Client;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

class CartController extends Controller
{
    public function behaviors()
    {
    return array_merge(
        parent::behaviors(),
        [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['index', 'momo-payment', 'thank-you'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'], // @ nghĩa là đã đăng nhập
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ]
    );
    }
    public function actionIndex()
    {
        $items = CartItem::getItems();
        $total = CartItem::getTotal();
        return $this->render('index', [
            'items' => $items,
            'total' => $total,
        ]);
    }

    public function actionAdd($id)
    {
        CartItem::add($id);
        Yii::$app->session->setFlash('success', 'Sản phẩm đã được thêm vào giỏ hàng!');
        return $this->redirect(Yii::$app->request->referrer ?: ['cart/index']);
    }

    public function actionClear()
    {
        CartItem::clear();
        Yii::$app->session->setFlash('info', 'Giỏ hàng đã được xóa.');
        return $this->redirect(['index']);
    }

    // controllers/CartController.php


    public function actionCheckout()
    {
        $items = CartItem::getItems();
        if (empty($items)) {
            Yii::$app->session->setFlash('error', 'Giỏ hàng trống.');
            return $this->redirect(['index']);
        }

        $total = CartItem::getTotal();
        if ($total <= 0) {
            Yii::$app->session->setFlash('error', 'Tổng tiền không hợp lệ.');
            return $this->redirect(['index']);
        }

        $orderId = uniqid();
        $orderData = [
            'order_id' => $orderId,
            'user_id' => Yii::$app->user->id,
            'total_price' => $total,
            'shipping_address' => Yii::$app->request->post('shipping_address', ''),
            'notes' => Yii::$app->request->post('notes', ''),
            'payment_method' => 'cash',
            'status' => 'paid',
            'items' => array_map(function ($item) {
                return [
                    'product_id' => (int)$item['product']->id,
                    'quantity' => (int)$item['quantity'],
                    'price' => (float)$item['product']->price,
                ];
            }, $items),
        ];

        try {
            // Gửi dữ liệu đến middleware Node.js
            $client = new Client();
            $response = $client->post('http://localhost:3001/add-order', [
                'json' => $orderData,
                'headers' => [
                    'Accept' => 'application/json',
                ],
            ]);

            $body = json_decode($response->getBody(), true);
            if ($body['success']) {
                Yii::$app->session->set('pending_order_id', $orderId);
                Yii::$app->session->set('order_total', $total);
                CartItem::clear();
                Yii::$app->session->setFlash('success', 'Đơn hàng đã được gửi và đang được xử lý.');
                return $this->redirect(['thank-you', 'orderId' => $orderId]);
            } else {
                throw new \Exception('Lỗi gửi đơn hàng');
            }
        } catch (\Exception $e) {
            Yii::$app->session->setFlash('error', 'Lỗi gửi đơn hàng: ' . $e->getMessage());
            return $this->redirect(['index']);
        }
    }



    public function actionRemove($id)
    {
        CartItem::remove($id);
        Yii::$app->session->setFlash('success', 'Đã xóa sản phẩm khỏi giỏ hàng.');
        return $this->redirect(['index']);
    }

    public function actionUpdateQuantity()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $productId = Yii::$app->request->post('id');
        $quantity = Yii::$app->request->post('quantity');

        $result = CartItem::updateQuantity($productId, $quantity);

        if ($result) {
            $total = CartItem::getTotal();
            $subtotal = CartItem::getSubtotal($productId);
            return [
                'success' => true,
                'total' => Yii::$app->formatter->asCurrency($total),
                'subtotal' => Yii::$app->formatter->asCurrency($subtotal),
            ];
        } else {
            return [
                'success' => false,
            ];
        }
    }

    public function actionCreatePayment()
    {
        $items = CartItem::getItems();
        if (empty($items)) {
            Yii::$app->session->setFlash('error', 'Giỏ hàng trống.');
            return $this->redirect(['index']);
        }

        $total = CartItem::getTotal();
        $orderId = uniqid();
        Yii::$app->session->set('temp_order_id', $orderId);
        Yii::$app->session->set('shipping_address', Yii::$app->request->post('shipping_address', ''));
        Yii::$app->session->set('notes', Yii::$app->request->post('notes', ''));

        $momoService = new MomoService();
        $response = $momoService->createPayment([
            'order_id' => $orderId,
            'amount' => $total,
            'order_info' => 'Thanh toán đơn hàng online',
        ]);

        if ($response['success'] && isset($response['pay_url'])) {
            return $this->redirect($response['pay_url']);
        }

        Yii::$app->session->setFlash('error', 'Yêu cầu thanh toán thất bại. Vui lòng thử lại.');
        return $this->redirect(['index']);
    }

    public function actionCallback()
{
    Yii::$app->response->format = Response::FORMAT_JSON;

    $momoService = new MomoService();
    $result = $momoService->paymentExecute(Yii::$app->request->get());

    if ($result['success']) {
        $items = CartItem::getItems();
        $total = CartItem::getTotal();
        if ($total <= 0) {
            return [
                'success' => false,
                'message' => 'Tổng tiền không hợp lệ.',
            ];
        }

        $orderId = Yii::$app->session->get('temp_order_id');
        $orderData = [
            'order_id' => $orderId,
            'user_id' => Yii::$app->user->id,
            'total_price' => $total,
            'shipping_address' => Yii::$app->session->get('shipping_address', ''),
            'notes' => Yii::$app->session->get('notes', ''),
            'payment_method' => 'momo',
            'status' => 'paid',
            'momo_transaction_id' => $result['trans_id'] ?? null,
            'items' => array_map(function ($item) {
                return [
                    'product_id' => (int)$item['product']->id,
                    'quantity' => (int)$item['quantity'],
                    'price' => (float)$item['product']->price,
                ];
            }, $items),
        ];

        try {
            // Gửi dữ liệu đến middleware Node.js
            $client = new Client();
            $response = $client->post('http://localhost:3001/add-order', [
                'json' => $orderData,
                'headers' => [
                    'Accept' => 'application/json',
                ],
            ]);

            $body = json_decode($response->getBody(), true);
            if ($body['success']) {
                Yii::$app->session->set('pending_order_id', $orderId);
                Yii::$app->session->set('order_total', $total);
                CartItem::clear();
                Yii::$app->session->remove('temp_order_id');
                Yii::$app->session->remove('shipping_address');
                Yii::$app->session->remove('notes');
                return $this->redirect(['thank-you', 'orderId' => $orderId]);
            } else {
                throw new \Exception('Lỗi gửi đơn hàng');
            }
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Lưu đơn hàng thất bại: ' . $e->getMessage(),
            ];
        }
    }

    return [
        'success' => false,
        'message' => $result['message'],
    ];
}

    
public function actionThankYou($orderId)
{
    $pendingOrderId = Yii::$app->session->get('pending_order_id');
    if ($orderId !== $pendingOrderId) {
        throw new \yii\web\NotFoundHttpException('Không tìm thấy đơn hàng.');
    }

    $client = new \GuzzleHttp\Client();
    try {
        $response = $client->get("http://localhost:3000/order/{$orderId}");
        $order = json_decode($response->getBody(), true);

        if ($order && isset($order['id'])) {
            Yii::$app->session->remove('pending_order_id');
            return $this->render('thank-you', [
                'order' => $order,
                'total' => $order['total_price'] ?? 0,
            ]);
        }
    } catch (\Exception $e) {
        $total = Yii::$app->session->get('order_total', 0);
        Yii::debug("Error fetching order: {$e->getMessage()}", __METHOD__);
        return $this->render('thank-you', [
            'order' => ['id' => $orderId, 'status' => 'pending'],
            'total' => $total,
            'message' => 'Đơn hàng của bạn đang được xử lý. Vui lòng kiểm tra lại sau vài phút.',
        ]);
    }

    throw new \yii\web\NotFoundHttpException('Không tìm thấy đơn hàng.');
}
//     public function actionTestEnv()
// {
//     var_dump($_ENV['MOMO_PARTNER_CODE']);
//     var_dump($_ENV['MOMO_ACCESS_KEY']);
//     var_dump($_ENV['MOMO_SECRET_KEY']);
//     Yii::$app->end(); // Dừng để xem kết quả
// }

}