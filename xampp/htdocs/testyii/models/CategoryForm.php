<?php

namespace app\models;

use yii\base\Model;
use app\models\dao\Category;

class CategoryForm extends Model
{
    public $id;
    public $name;
    public $description;

    private $_savedModel;

    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name', 'description'], 'string', 'max' => 255],
        ];
    }

    // Load dữ liệu từ Category vào CategoryForm
    public function loadFromCategory($id)
    {
        $category = Category::findOne($id);

        if ($category) {
            $this->id = $category->id;
            $this->name = $category->name;
            $this->description = $category->description;
        }
    }

    // Lưu hoặc cập nhật Category
    public function save()
    {
        $category = $this->id ? Category::findOne($this->id) : new Category();

        if (!$category) {
            return false;
        }

        $category->name = $this->name;
        $category->description = $this->description;

        if ($category->save(false)) {
            $this->_savedModel = $category;
            return true;
        }

        return false;
    }

    // Lấy Model đã được lưu
    public function getSavedModel()
    {
        return $this->_savedModel;
    }

    // Xóa Category
    public function delete()
    {
        $category = Category::findOne($this->id);

        if ($category) {
            return $category->delete();
        }

        return false;
    }

    // Tìm một Category theo ID
    public static function findOne($id)
    {
        $category = Category::findOne($id);
        $form = new self();
        
        if ($category) {
            $form->loadFromCategory($category->id);
            return $form;
        }
        
        return null;
    }
}
