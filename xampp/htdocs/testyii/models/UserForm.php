<?php

namespace app\models;

use Yii;
use yii\base\Model;

class UserForm extends Model
{
    public $id;
    public $username;
    public $password;
    public $confirm_password;
    public $password_hash;
    public $auth_key;
    public $access_token;

    public function rules()
    {
        return [
            [['username'], 'required'],
            [['username'], 'string', 'max' => 255],
            [['password', 'confirm_password'], 'safe'],
            [['password'], 'string', 'min' => 4],
            ['confirm_password', 'compare', 'compareAttribute' => 'password', 'message' => 'Passwords do not match.'],
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            return false;
        }

        $user = new User();
        $user->username = $this->username;
        $user->password_hash = Yii::$app->security->generatePasswordHash($this->password);
        $user->auth_key = Yii::$app->security->generateRandomString();
        $user->access_token = Yii::$app->security->generateRandomString();

        return $user->save() ? $user : false;
    }

    public function loadFromUser(User $user)
    {
        $this->id = $user->id;
        $this->username = $user->username;
    }

    public function update(User $user)
    {
        if (!$this->validate()) {
            return false;
        }

        if (!empty($this->password)) {
            $user->password_hash = Yii::$app->security->generatePasswordHash($this->password);
        }

        return $user->save(false) ? $user : false;
    }
}
