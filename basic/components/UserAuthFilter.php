<?php
namespace app\components;

use Yii;

//use yii\base\CFilter;

class UserAuthFilter
{
    public function checkAuth() {
        $user = Yii::$app->user;
        if (!Yii::$app->user) {
            return $this->render('login');
        }
    }
}