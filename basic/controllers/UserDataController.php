<?php

namespace app\controllers;
//use yii\web\Controller;
use app\components\util\Util;
use app\controllers\LoginCheckController as Controller;
use app\models\UserData;
use yii\data\Pagination;


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
        $tf_type = $request->get('tf_type') ?: '3';



        $params = [
            'c.user_id'=>$user_id,
            'c.tf_type' => $tf_type
        ];
        $today = date("Y-m-d", mktime());

        $params['date_at'] = $date_at;

        $query = UserData::find()
            ->select('c.ad_sc_title,user_data.*,c.tf_type')
            ->leftJoin('courses as c','c.id=user_data.course_id')
            ->where($params);

        $page = new Pagination([
            'defaultPageSize' => 10,
            'totalCount' => $query->count(),
            'pageParam' => 'page',
        ]);
        if ($is_export) {
            $datas = $query->asArray()->all();
        } else {
            $datas = $query->offset($page->offset)
                ->limit($page->limit)
                //->orderBy()
                ->asArray()->all();
        }
        $resDatas = [];

        if ($tf_type == '3') {  //CPA
            $titles = ["项目名称", "手机号", "地区"];
            foreach ($datas as $data) {
                $item = [];
                $item['title'] = $data['ad_sc_title'];
                $item['mobile'] = $data['info_mobile'];
                $item['city'] = array_search($data['info_city'], \Yii::$app->params['province']);
                $resDatas[] = $item;
            }
        }
        if ($tf_type == '4') {  //CPL
            $titles = ["项目名称", "手机号", "姓名", "性别", "地区", "公司信息", "职位信息"];
            foreach ($datas as $data) {
                $item = [];
                $item['title'] = $data['ad_sc_title'];
                $item['mobile'] = $data['info_mobile'];
                $item['name'] = $data['info_name'].($data['info_gender'] == '1' ?  "先生" : "女士");
                $item['gender'] = $data['info_gender'] == '1' ? "男" : "女";
                $item['city'] = array_search($data['info_city'], \Yii::$app->params['province']);

                $age = '';
                if ($data['info_tags']) {
                    $tags = explode('A', strtoupper($data['info_tags']));
                    if (count($tags)>1) {
                        $tag1 = explode(',', $tags[1]);
                        $age = $tag1[0];
                    }
                }
                $item['age'] = $age;
                $item['position'] = $data['info_position'];
                $resDatas[] = $item;
            }
        }

        if ($is_export) {
            Util::ExportCsv($resDatas, $titles, $date_at);
        }
        return $this->render('index',[
            'date_at' => $date_at,
            'tf_type' => $tf_type,
            'titles' => $titles,
            'datas'=>$resDatas,
            'page'=>$page
        ]);
    }
}
