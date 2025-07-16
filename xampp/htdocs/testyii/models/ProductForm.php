<?php

namespace app\models;

use yii\base\Model;
use yii\web\UploadedFile;
use app\models\dao\Product;
class ProductForm extends Model
{
    public $id;
    public $name;
    public $description;
    public $price;
    public $category_id;
    public $image;
    public $imageFile;

    public function rules()
    {
        return [
            [['name', 'price', 'category_id'], 'required'],
            [['price'], 'number'],
            [['category_id'], 'integer'],
            [['description'], 'string'],
            [['name', 'image'], 'string', 'max' => 255],
            [['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg'],
        ];
    }

    public function loadFromProduct(Product $product)
    {
        $this->id = $product->id;
        $this->name = $product->name;
        $this->description = $product->description;
        $this->price = $product->price;
        $this->category_id = $product->category_id;
        $this->image = $product->image;
    }

    public function save()
    {
        $product = $this->id ? Product::findOne($this->id) : new Product();

        if (!$product) return false;

        $product->name = $this->name;
        $product->description = $this->description;
        $product->price = $this->price;
        $product->category_id = $this->category_id;

        $this->imageFile = UploadedFile::getInstance($this, 'imageFile');
        if ($this->imageFile) {
            $filename = uniqid() . '.' . $this->imageFile->extension;
            $this->imageFile->saveAs('uploads/' . $filename);
            $product->image = 'uploads/' . $filename;
        }

        return $product->save(false) ? $product : false;
    }
}
