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
use yii\web\ForbiddenHttpException;


class LoginCheckController extends Controller
{

    public function beforeAction($action)
    {
        if(!Yii::$app->user->id) {
            if (Yii::$app->request->url == "/index.php?r=site/login" ) {
                return true;
            }
            $this->redirect('/index.php?r=site/login');
        } else {
            $is_admin = Yii::$app->user->getIdentity('"_identity":"yii\web\User"')['is_admin'];
            $req_url = urldecode(urldecode(Yii::$app->request->url));
            $check_prefix = '?r=user';
            $pattern = "/\/[\w\.]*\?r=user\/(\w)*/i";
            if (!$is_admin) {
//                $req_url = urldecode(urldecode(Yii::$app->request->url));
//                $check_prefix = '?r=user';
//                $pattern = "/\/[\w\.]*\?r=user\/(\w)*/i";
//                //var_dump(preg_match($pattern, $req_url));
                // strrchar(a,b)查找字符在指定字符串中从左面开始的最后一次出现的位置,如果成功,返回该字符以及其后面的字符,如果失败,则返回 NULL
                //var_dump(strrchr($req_url,$check_prefix)==$check_prefix);
                if (strrchr($req_url,$check_prefix)==$check_prefix || preg_match($pattern, $req_url)) {
                    //throw new ForbiddenHttpException(Yii::t('app', 'message 401'));
                    $this->redirect('/index.php?r=site/error-page&message='."!权限不足，请联系管理员再试");
                }
            } else {
                if (strrchr($req_url,$check_prefix)==$check_prefix || preg_match($pattern, $req_url)) {
                    return true;
                } else {
                    $this->redirect('/index.php?r=user');
                }
            }
            return parent::beforeAction($action);
        }
    }

}