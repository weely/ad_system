<?php

namespace app\controllers;
//use yii\web\Controller;
use app\controllers\LoginCheckController as Controller;


class UserDataController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

}
