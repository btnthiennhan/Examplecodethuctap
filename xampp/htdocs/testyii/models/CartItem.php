<?php
namespace app\models;

use Yii;
use app\models\dao\Product;

class CartItem
{
    public static function add($productId, $quantity = 1)
    {
        $cart = Yii::$app->session->get('cart', []);

        if (isset($cart[$productId])) {
            $cart[$productId] += $quantity;
        } else {
            $cart[$productId] = $quantity;
        }

        Yii::$app->session->set('cart', $cart);
    }

    public static function getItems()
{
    $cart = Yii::$app->session->get('cart', []);
    $items = [];
    foreach ($cart as $productId => $quantity) {
        $product = Product::findOne($productId);
        if ($product) {
            $items[] = [
                'product' => $product,
                'quantity' => $quantity,
                'subtotal' => $product->price * $quantity,
            ];
        }
    }
    return $items;
}

public static function getTotal()
{
    $items = self::getItems();
    $total = 0;
    foreach ($items as $item) {
        Yii::debug("Product ID: {$item['product']->id}, Price: {$item['product']->price}, Quantity: {$item['quantity']}, Subtotal: {$item['subtotal']}", __METHOD__);
        $total += $item['subtotal'];
    }
    Yii::debug("Total: $total", __METHOD__);
    return $total;
}

    public static function clear()
    {
        Yii::$app->session->remove('cart');
    }

    public static function remove($productId)
    {
        $cart = Yii::$app->session->get('cart', []);
        unset($cart[$productId]);
        Yii::$app->session->set('cart', $cart);
    }

    public static function updateQuantity($productId, $quantity)
    {
        $cart = Yii::$app->session->get('cart', []);
        if (isset($cart[$productId])) {
            $cart[$productId] = $quantity;
            Yii::$app->session->set('cart', $cart);
            return true;
        }
        return false;
    }

    public static function getSubtotal($productId)
    {
        $items = self::getItems();
        foreach ($items as $item) {
            if ($item['product']->id == $productId) {
                return $item['subtotal'];
            }
        }
        return 0;
    }
}
