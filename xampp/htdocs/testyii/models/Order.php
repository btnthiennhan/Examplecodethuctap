<?php
// models/Order.php
namespace app\models;
use app\models\dao\Product;
use Yii;
use yii\db\ActiveRecord;

class Order extends ActiveRecord
{
    public static function tableName()
    {
        return 'order';
    }

    public function rules()
    {
        return [
            [['user_id', 'total_price', 'payment_method', 'status'], 'required'],
            [['user_id'], 'integer'],
            [['order_date'], 'safe'],
            [['total_price'], 'number'],
            [['shipping_address', 'notes'], 'string', 'max' => 255],
            [['payment_method'], 'string', 'max' => 50],
            [['status'], 'string', 'max' => 20],
            [['momo_transaction_id'], 'string', 'max' => 100],
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function getOrderDetails()
    {
        return $this->hasMany(OrderDetail::class, ['order_id' => 'id']);
    }
}