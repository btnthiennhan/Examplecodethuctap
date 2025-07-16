<?php
// components/MomoService.php
namespace app\components;

use Yii;
use yii\base\Component;
use yii\httpclient\Client;
use app\models\CartItem;
use app\models\Order;
use app\models\OrderDetail;

class MomoService extends Component
{
    protected $apiUrl;
    protected $secretKey;
    protected $accessKey;
    protected $returnUrl;
    protected $notifyUrl;
    protected $partnerCode;
    protected $requestType;
    protected $exchangeRate;

    public function init()
    {
        parent::init();
        $this->apiUrl = Yii::$app->params['momo']['api_url'] ?? '';
        $this->secretKey = Yii::$app->params['momo']['secret_key'] ?? '';
        $this->accessKey = Yii::$app->params['momo']['access_key'] ?? '';
        $this->returnUrl = Yii::$app->params['momo']['return_url'] ?? '';
        $this->notifyUrl = Yii::$app->params['momo']['notify_url'] ?? '';
        $this->partnerCode = Yii::$app->params['momo']['partner_code'] ?? '';
        $this->requestType = Yii::$app->params['momo']['request_type'] ?? '';
        $this->exchangeRate = Yii::$app->params['momo']['exchange_rate'] ?? 24000;

        // Ghi log cấu hình
        Yii::info('MoMo Config: ' . json_encode([
            'api_url' => $this->apiUrl,
            'partner_code' => $this->partnerCode,
            'access_key' => $this->accessKey,
            'secret_key' => '[hidden]',
            'return_url' => $this->returnUrl,
            'notify_url' => $this->notifyUrl,
            'request_type' => $this->requestType,
            'exchange_rate' => $this->exchangeRate,
        ]), 'momo');

        // Kiểm tra cấu hình
        $requiredConfigs = [
            'api_url' => $this->apiUrl,
            'secret_key' => $this->secretKey,
            'access_key' => $this->accessKey,
            'partner_code' => $this->partnerCode,
        ];
        foreach ($requiredConfigs as $key => $value) {
            if (empty($value)) {
                Yii::error("Cấu hình MoMo thiếu hoặc không hợp lệ: $key", 'momo');
                throw new \yii\base\InvalidConfigException("Cấu hình MoMo thiếu: $key");
            }
        }
    }

    public function createPayment(array $orderInfo)
    {
        // Kiểm tra apiUrl trước khi gửi yêu cầu
        Yii::info('MoMo API URL: ' . $this->apiUrl, 'momo');
        if (empty($this->apiUrl)) {
            Yii::error('MoMo API URL is empty', 'momo');
            return [
                'success' => false,
                'message' => 'Cấu hình MoMo API URL không hợp lệ',
            ];
        }
    
        $requestId = time() . '';
        $orderId = $orderInfo['order_id'];
        $extraData = 'user_id=' . Yii::$app->user->id;
    
        $amountVnd = $orderInfo['amount'];
    
        $orderInfoText = $orderInfo['order_info'] ?? "Thanh toán đơn hàng #$orderId";
    
        $rawHash = "accessKey={$this->accessKey}&amount={$amountVnd}&extraData={$extraData}&ipnUrl={$this->notifyUrl}&orderId={$orderId}&orderInfo={$orderInfoText}&partnerCode={$this->partnerCode}&redirectUrl={$this->returnUrl}&requestId={$requestId}&requestType={$this->requestType}";
        $signature = hash_hmac('sha256', $rawHash, $this->secretKey);
    
        $requestData = [
            'partnerCode' => $this->partnerCode,
            'accessKey' => $this->accessKey,
            'requestId' => $requestId,
            'amount' => (int)$amountVnd,
            'orderId' => $orderId,
            'orderInfo' => $orderInfoText,
            'redirectUrl' => $this->returnUrl,
            'ipnUrl' => $this->notifyUrl,
            'extraData' => $extraData,
            'requestType' => $this->requestType,
            'signature' => $signature,
        ];
    
        // In ra hoặc ghi log toàn bộ dữ liệu gửi đi
        Yii::info('MoMo Request Data: ' . json_encode($requestData, JSON_PRETTY_PRINT), 'momo');
    
        // // Nếu muốn in trực tiếp ra màn hình giống var_dump (chỉ dùng khi debug)
        // if (YII_DEBUG) {
        //     echo '<pre>';
        //     var_dump($requestData);
        //     echo '</pre>';
        //     Yii::$app->end(); // Dừng để xem kết quả
        // }
    
        $client = new Client();
        $response = $client->createRequest()
            ->setMethod('POST')
            ->setUrl($this->apiUrl)
            ->setFormat(Client::FORMAT_JSON)
            ->setData($requestData)
            ->send();
    
        if ($response->isOk) {
            $responseData = $response->data;
            Yii::info('MoMo API Response: ' . json_encode($responseData), 'momo');
            return [
                'success' => true,
                'pay_url' => $responseData['payUrl'] ?? null,
                'qr_code_url' => $responseData['qrCodeUrl'] ?? null,
                'message' => $responseData['message'] ?? 'Success',
            ];
        } else {
            Yii::error('MoMo API Error: ' . $response->content, 'momo');
            return [
                'success' => false,
                'message' => 'Không thể kết nối với MoMo API: ' . $response->content,
            ];
        }
    }

    public function paymentExecute($data)
    {
        Yii::info('MoMo Callback Data: ' . json_encode($data), 'momo');

        $requiredFields = [
            'partnerCode', 'orderId', 'requestId', 'amount', 'orderInfo',
            'orderType', 'transId', 'resultCode', 'message', 'payType',
            'responseTime', 'extraData', 'signature'
        ];

        foreach ($requiredFields as $field) {
            if (!isset($data[$field])) {
                Yii::error("Thiếu trường bắt buộc: $field", 'momo');
                return [
                    'success' => false,
                    'message' => "Thiếu trường: $field",
                ];
            }
        }

        parse_str($data['extraData'], $extraData);
        $userId = $extraData['user_id'] ?? null;

        if (!$userId) {
            Yii::error('Không tìm thấy user_id trong extraData', 'momo');
            return [
                'success' => false,
                'message' => 'Thiếu user_id trong extraData',
            ];
        }

        $rawHash = "accessKey={$this->accessKey}&amount={$data['amount']}&extraData={$data['extraData']}&message={$data['message']}&orderId={$data['orderId']}&orderInfo={$data['orderInfo']}&orderType={$data['orderType']}&partnerCode={$data['partnerCode']}&payType={$data['payType']}&requestId={$data['requestId']}&responseTime={$data['responseTime']}&resultCode={$data['resultCode']}&transId={$data['transId']}";
        $signature = hash_hmac('sha256', $rawHash, $this->secretKey);

        if ($signature !== $data['signature']) {
            Yii::error('Chữ ký không hợp lệ', 'momo');
            return [
                'success' => false,
                'message' => 'Chữ ký không hợp lệ',
            ];
        }

        if ($data['resultCode'] == '0') {
            try {
                $cartItems = CartItem::getItems();
                $total = CartItem::getTotal();

                $order = new Order();
                $order->user_id = $userId;
                $order->order_date = date('Y-m-d H:i:s');
                $order->total_price = $total;
                $order->shipping_address = Yii::$app->session->get('shipping_address', '');
                $order->notes = 'MoMo Transaction ID: ' . $data['transId'];
                $order->payment_method = 'online';
                $order->status = 'paid';
                $order->momo_transaction_id = $data['transId'];
                $order->save();

                foreach ($cartItems as $item) {
                    $orderDetail = new OrderDetail();
                    $orderDetail->order_id = $order->id;
                    $orderDetail->product_id = $item['product']->id;
                    $orderDetail->quantity = $item['quantity'];
                    $orderDetail->price = $item['product']->price;
                    $orderDetail->save();
                }

                CartItem::clear();

                return [
                    'success' => true,
                    'order_id' => $order->id,
                    'message' => 'Thanh toán thành công',
                ];
            } catch (\Exception $e) {
                Yii::error('Lỗi xử lý đơn hàng: ' . $e->getMessage(), 'momo');
                return [
                    'success' => false,
                    'message' => 'Không thể xử lý đơn hàng: ' . $e->getMessage(),
                ];
            }
        }

        Yii::warning('Thanh toán thất bại: ' . $data['message'], 'momo');
        return [
            'success' => false,
            'message' => 'Thanh toán thất bại: ' . $data['message'],
        ];
    }
}