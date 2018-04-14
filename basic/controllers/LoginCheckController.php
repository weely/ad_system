<?php
/**
 * Created by PhpStorm.
 * User: Lw
 * Date: 2018/4/1
 * Time: 18:25
 */

namespace app\controllers;

use Yii;
use yii\web\Controller;

class LoginCheckController extends Controller
{

    public function beforeAction($action)
    {
        /*
        if(!Yii::$app->user->isGuest){
            return parent::beforeAction($action);
        }
        */
        if(!Yii::$app->user->id) {
//            if (strtoupper($_SERVER['REQUEST_METHOD']) === 'GET' && Yii::$app->request->url == "/index.php?r=site/login" ) {
            if (Yii::$app->request->url == "/index.php?r=site/login" ) {
                return true;
            }
            $this->redirect('/index.php?r=site/login');
        } else {
            return parent::beforeAction($action);
        }
    }

}