<?php

namespace app\controllers;
//use yii\web\Controller;
use app\components\util\Util;
use app\controllers\LoginCheckController as Controller;
use app\models\HisUserData;
use app\models\UserData;


class UserDataController extends Controller
{
    public function actionIndex()
    {
        $user_id = \Yii::$app->user->id;
        $request = \Yii::$app->request;


        return $this->render('index');
    }


    public function actionDayDatas()
    {
        $user_id = \Yii::$app->user->id;
        $request = \Yii::$app->request;

//        $plan_id = $request->get('plan_id');
        $date_at = $request->get('date_at');
        $is_export = $request->get('export');

        $params = ['c.user_id'=>$user_id];
        $today = date("Y-m-d", mktime());

        if ($today == $date_at) {
            $datas = UserData::find()
                ->select('c.ad_sc_title,user_data.*')
                ->leftJoin('courses as c','c.id=user_data.course_id')
                ->where($params)
                //->orderBy()
                ->asArray()->all();

        } else {
            $params['date_at'] = $date_at;
            $datas = HisUserData::find()->select('c.ad_sc_title,his_user_data.*')
                ->leftJoin('courses as c','c.id=his_user_data.course_id')
                ->where($params)
                //->orderBy()
                ->asArray()->all();
        }
        $resDatas = [];
        $titles = ["项目名称", "手机号", "姓名", "性别", "地区", "公司信息", "职位信息"];
        foreach ($datas as $data) {
            $item = [];
            $item['title'] = $data['ad_sc_title'];
            $item['mobile'] = $data['info_mobile'];
            $item['name'] = $data['info_name'];
            $item['gender'] = $data['info_gender'];
            $item['city'] = $data['info_city'];
            $item['age'] = $data['info_tags'];
            $item['position'] = $data['info_position'];
            $resDatas[] = $item;
        }

        if ($is_export) {
            Util::ExportCsv($resDatas, $titles, $date_at);
        }
        return $this->render('index',[
            'date_at' => $date_at,
            'titles' => $titles,
            'datas'=>$resDatas
        ]);
    }
}
