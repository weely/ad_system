<?php

namespace app\controllers;

use app\models\FundFlows;
use app\models\UserData;
use Yii;
use yii\data\Pagination;
use yii\filters\AccessControl;
//use yii\web\Controller;
use app\controllers\LoginCheckController as Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\AdPlans;
use app\models\Courses;
use app\models\AdPlansSearch;
use app\models\CoursesSearch;
use app\components\util\Util;
//use app\components\UserAuthFilter as checkUser;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays 数据中心首页.
     *
     * @return string
     */
    public function actionIndex()
    {
        $user_id = Yii::$app->user->id;
        $request = Yii::$app->request;

        $plan_id = $request->get('plan_id');
        $c_id = $request->get('c_id');
//        $order_value = $request->get('order_value') ?: 'date_at';
        $order_value = 'user_data.date_at';
        //1-> asc, 0->desc;
        $order_type = $request->get('order_value') && $request->get('order_value')==1  ? 'ASC' : 'DESC';
        $begin_time = $request->get("begin_time");
        $end_time = $request->get("end_time");
        $is_export = $request->get('export');

        $apQuery = AdPlans::find();
        $cQuery = Courses::find();
        /*参数字段*/
        $aPparams = ['user_id'=>$user_id];              //广告计划查询参数
        $cParams = ['user_id'=>$user_id];               //广告素材查询参数
        $dataParams = ['user_data.user_id' => $user_id];            //数据查询参数
        $dgpParams = 'user_data.date_at';            //分组参数
        $data_sql = ['plan.tf_type', 'plan.tf_value', 'sum(h.show_num) as show_num', 'sum(h.click_num) as click_num',
            'sum(h.book_num) as book_num', 'user_data.date_at'];

        /*查询数据*/
        $userPlans = $apQuery->select('id,plan_name')->where($aPparams)->andWhere(['<>','tf_status','4'])->orderBy('id')->asArray()->all();
        $userCourses = $cQuery->select('id,ad_sc_title')->where($cParams)->andWhere(['<>','courses.tf_status','4'])->orderBy('id')->asArray()->all();

        array_unshift($userPlans, ['id'=>'-1', 'plan_name'=>'全部计划']);
        array_unshift($userCourses, ['id'=>'-1', 'ad_sc_title'=>'全部素材']);

        if (!$plan_id && !$c_id){
            $data_sql[] = 'user_data.user_id';

            $dgpParams .= ',user_data.user_id';
        }
        if ($plan_id) {
            $aPparams['id'] = $plan_id;
            $cParams['plan_id'] = $plan_id;

            $dataParams['user_data.plan_id'] = $plan_id;
            $data_sql[] = 'user_data.plan_id';
            $dgpParams .= ',user_data.plan_id';
        }
        if ($c_id) {
            $cParams['id'] = $c_id;

            $dataParams['user_data.course_id'] = $c_id;
            $data_sql[] = 'user_data.course_id';
            $dgpParams .= ',user_data.course_id';
        }
        //投放状态： 0-> 待审核  ，1->投放中，  2->待投放
        $cParams['tf_status'] = '1';
        $tfz = count($cQuery->where($cParams)->all());
        $cParams['tf_status'] = '2';
        $dtf = count($cQuery->where($cParams)->all());
        $cParams['tf_status'] = '0';
        $dsk = count($cQuery->where($cParams)->all());

        $today = date("Y-m-d", mktime());
        $flag = '';

        $dataQuery = UserData::find();
        $filterInParams = [];
        if ($begin_time && $end_time) {
            if ($begin_time > $end_time) {
                return "";
            }
            $yesday = date("Y-m-d",strtotime("-1 day"));
            if ($begin_time == $end_time) {
                if ($end_time == $today) {
                    $flag = 'today';
                }
                if ($end_time == $yesday) {
                    $flag = 'yestoday';
                }
                $data_sql[] = "date_format(user_data.create_at, '%H') as hour";
                $dataParams['date_at'] = $end_time;

                //$filterInParams = "date_format(user_data.create_at, '%H') = date_format(h.time, '%H')";
                $dgpParams .= ',hour';

                $order_value = 'hour';
            } else{
                if ($end_time == $yesday && strtotime($end_time)-strtotime($begin_time)==6*24*3600) {
                    $flag = 'week';
                }
                $filterInParams = ['between', 'user_data.date_at',$begin_time, $end_time];
            }
        }

        $dataQuery = $dataQuery->select($data_sql)
            ->leftJoin('show_hours as h','h.`cid`=`user_data`.`course_id` 
                and date_format(user_data.create_at, \'%Y-%m-%d %H\') = date_format(h.time, \'%Y-%m-%d %H\')')
            ->leftJoin('ad_plans as plan','plan.`id`=`user_data`.`plan_id`')
            ->leftJoin('courses as c','c.`id`=`user_data`.`course_id`')
            ->where($dataParams)
            ->andWhere($filterInParams)
//            ->andWhere(['<>','plan.tf_status','4'])
//            ->andWhere(['<>','c.tf_status','4'])
            ->groupBy($dgpParams);

        if ($is_export == '1') {
            $datas = $dataQuery->orderBy($order_value .' '. $order_type)
                ->asArray()->all();
        } else {
            if ($begin_time != '' && $begin_time == $end_time) {
                $page = new Pagination([
                    'defaultPageSize' => 24,
                    'totalCount' => $dataQuery->count(),
                ]);
            } else {
                $page = new Pagination([
                    'defaultPageSize' => 9,
                    'totalCount' => $dataQuery->count(),
                ]);
            }

            $datas = $dataQuery->orderBy($order_value .' '. $order_type)
                ->offset($page->offset)->limit($page->limit)
                ->asArray()->all();
        }

        //折线图数据
        $xAxis = [];
        $show_num_arr = [];
        $click_num_arr = [];
        $book_num_arr = [];
        $click_rate_arr = [];
        $book_rate_arr = [];
        $click_book_rate_arr = [];

        $sumClick = 0;
        $sumbook = 0;
        $sumshow = 0;
        $sumClickRate = 0;
        $sumBookRate = 0;
        $sumCBRate = 0;
        $sumDayCost = 0;
        $resData = [];

        foreach ($datas as $data) {
            $data['click_rate'] = round(($data['show_num']>0 ? $data['click_num'] / $data['show_num'] : 0),4)*100;
            $data['book_rate'] = round(($data['show_num']>0 ? $data['book_num'] / $data['show_num'] : 0),4)*100;
            $data['click_book_rate'] = round(($data['click_num']>0 ? $data['book_num'] / $data['click_num'] : 0),4)*100;
            // TODO 日消耗暂时未计算
            // 1:cpm, 2:cpc, 3:cpa, 4:cpl, 5:cps
            if ($data['tf_type'] == '4') {
                $data['day_cost'] = $data['tf_value'] * $data['book_num'];
            } else if ($data['tf_type'] == '3' || $data['tf_type'] == '2' ) {
                $data['day_cost'] = $data['tf_value'] * $data['click_num'];
            }
            $sumshow += $data['show_num'];
            $sumbook += $data['book_num'];
            $sumClick += $data['click_num'];
            $sumDayCost += $data['day_cost'];
            $resData[] = $data;

            if ($end_time != '' && $end_time == $begin_time) {
                $xAxis[] = $data['hour'];
            } else {
                $xAxis[] = $data['date_at'];
            }
            $show_num_arr[] = $data['show_num'];
            $click_num_arr[] = $data['click_num'];
            $book_num_arr[] = $data['book_num'];
            $click_rate_arr[] = $data['click_rate'];
            $book_rate_arr[] = $data['book_rate'];
            $click_book_rate_arr[] = $data['click_book_rate'];
        }
        $sumClickRate = round(($sumshow>0 ? $sumClick/$sumshow : 0),4)*100;
        $sumBookRate = round(($sumshow>0 ? $sumbook/$sumshow : 0),4)*100;
        $sumCBRate = round(($sumClick>0 ? $sumbook/$sumClick : 0),4)*100;
        // TODO 日消耗暂时未计算
        // $sumDatas = ['总计', $sumshow, $sumClick, $sumClickRate, $sumbook, $sumBookRate,$sumCBRate , $sumDayCost];
        $sumDatas = ['hour'=>'当日总计','date_at'=>'总计','show_num'=> $sumshow,'click_num'=> $sumClick, 'click_rate'=>$sumClickRate,
            'book_num'=>$sumbook, 'book_rate'=>$sumBookRate, 'click_book_rate'=>$sumCBRate , 'day_cost'=>$sumDayCost];
        array_unshift($resData, $sumDatas);

        if ($is_export == '1') {
            $titles = ['日期','曝光数','点击数','点击率','预约数','预约率','点击预约率','日消耗(元)'];
            $exportArr = [];
            foreach ($resData as $value){
                $temp = [];
                $temp['date_at'] = $value['date_at'];
                $temp['show_num'] = $value['show_num'];
                $temp['click_num'] = $value['click_num'];
                $temp['click_rate'] = $value['click_rate'];
                $temp['book_num'] = $value['book_num'];
                $temp['book_rate'] = $value['book_rate'];
                $temp['click_book_rate'] = $value['click_book_rate'];
                $temp['day_cost'] = $value['day_cost'];
                $exportArr[] = $temp;
            }
            Util::ExportCsv($exportArr, $titles, $today);
        }

        $c_id = count($userCourses)>0 ? (isset($c_id) ? $c_id : $userCourses[0]['id']) : '-1';
        $plan_id = count($userPlans)>0 ? (isset($plan_id) ? $plan_id : $userPlans[0]['id']) : '-1';

        $datas = [
            'tableTitles' => [
                '日期','曝光数','点击数','点击率','预约数','预约率','点击预约率','日消耗(元)'
            ],
            'tfz' => $tfz,
            'dtf' => $dtf,
            'dsk' => $dsk,
            //'today_data' => $todayDatas,
            'datas' => $resData,
            //'sum_data' => $sumDatas
        ];

        //处理按小时显示图表
        if ($end_time != '' && $end_time == $begin_time) {
            $hourAxis = ["00","01","02","03","04","05","06","07","08","09","10","11","12",
                 "13","14","15","16","17","18","19","20","21","22","23","24"];
            $hour_show_num_arr = [];
            $hour_click_num_arr = [];
            $hour_book_num_arr = [];
            $hour_click_rate_arr = [];
            $hour_book_rate_arr = [];
            $hour_click_book_rate_arr = [];
            foreach ($hourAxis as $axi) {
                if (in_array($axi, $xAxis)) {
                    $hour_show_num_arr[] = array_pop($show_num_arr);
                    $hour_click_num_arr[] = array_pop($click_num_arr);
                    $hour_click_rate_arr[] = array_pop($click_rate_arr);
                    $hour_book_num_arr[] = array_pop($book_num_arr);
                    $hour_book_rate_arr[] = array_pop($book_rate_arr);
                    $hour_click_book_rate_arr[] = array_pop($click_book_rate_arr);
                } else {
                    $hour_show_num_arr[] = 0;
                    $hour_click_num_arr[] = 0;
                    $hour_click_rate_arr[] = 0;
                    $hour_book_num_arr[] = 0;
                    $hour_book_rate_arr[] = 0;
                    $hour_click_book_rate_arr[] = 0;
                }
            }
            $xAxis = $hourAxis;
            $show_num_arr = $hour_show_num_arr;
            $click_num_arr = $hour_click_num_arr;
            $book_num_arr = $hour_book_num_arr;
            $click_rate_arr = $hour_click_rate_arr;
            $book_rate_arr = $hour_book_rate_arr;
            $click_book_rate_arr = $hour_click_book_rate_arr;
        }

//        var_dump($click_rate_arr);
//        var_dump($book_rate_arr);
//        var_dump($click_book_rate_arr);
//        return;

        return $this->render('index',[
            'plans' => $userPlans,
            'courses' => $userCourses,
            'sel_plan' => $plan_id,
            'sel_course' => $c_id,
            'begin_time' => $begin_time,
            'flag' => $flag,
            'end_time' => $end_time,
            'data' => $datas,
            'echart_data' => [
                'xaxis' => $xAxis,
                'show_num' => $show_num_arr,
                'click_num' => $click_num_arr,
                'book_num' => $book_num_arr,
                'click_rate' => $click_rate_arr,
                'book_rate' => $book_rate_arr,
                'click_book_rate' => $click_book_rate_arr,
            ],
            'page' => $page
        ]);
    }

    /**
     * 登录.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
//        if (!Yii::$app->user->isGuest) {
//            return $this->goHome();
//        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->redirect('index');
        }
        return $this->render('login', [
            'model' => $model,
        ]);

        /*
        $model = new LoginForm();
        if (strtoupper($_SERVER['REQUEST_METHOD']) === 'GET') {
            return $this->render('login',[
                'model' => $model,
            ]);
        } else if (strtoupper($_SERVER['REQUEST_METHOD']) === 'POST') {
            $request = Yii::$app->request;
            $password = $request->post('LoginForm')['password'];
            if ($model->load(Yii::$app->request->post()) && $model->login($password)) {
                echo $password;
                var_dump($model);

                return $this->goBack();
            }
        }
        return $this->render('login',[
            'model' => $model,
        ]);*/
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->redirect('index.php?r=site%2Flogin');
    }

    /**
     * 意向用户页面
     *
     * @return
     */
    public function actionIntentUser()
    {
        $user_id = Yii::$app->user->id;
        $request = Yii::$app->request;

        $req_year = $request->get('year');
        $req_tf_type = $request->get('tf_type') ?: '3';

        $params = [
            'user_data.user_id' => $user_id,
            'c.tf_type' => $req_tf_type
        ];
        if ($req_year) {
            $params["DATE_FORMAT(`date_at`, '%Y')"] = $req_year;
        }

        $datas = UserData::find()->select('count(*) as nums,user_data.date_at')
            ->leftJoin('courses c','c.id=user_data.course_id')
            ->where($params)
            ->groupBy('user_data.date_at,course_id,c.tf_type')
            ->asArray()
            ->all();
        $resData = [];

        foreach ($datas as $data){
            $resData[$data['date_at']] = $data['nums'];
        }

        $resData['tf_type'] = $req_tf_type;

        return $this->render('intentuser',
            [
                'datas' => $resData,
                'total_nums' => count($resData),
                'year' => $req_year
            ]);
    }

    /**
     * 广告管理页面
     *
     * @return
     */
    public function actionAdManage(){
        $user_id = Yii::$app->user->id;
        $request = Yii::$app->request;
        $tf_status = $request->get('tf_status');
        $tf_type = $request->get('tf_type');
//        $pagTag = $request->page_tag;
        $sc_status = $request->get('sc_status');
        $selPlan = $request->get('sel_plan');

        $adpParams = ['user_id'=>$user_id];
        $scParams = ['courses.user_id'=>$user_id];

        $adPQuery = AdPlans::find();
        $scQuery = Courses::find();

        if ($tf_status && $tf_status != -1) {
            $adpParams['tf_status'] = $tf_status;
        }
        if ($tf_type && $tf_type != -1) {
            $adpParams['tf_type'] = $tf_type;
        }
        if ($sc_status && $sc_status != -1) {
            $scParams['courses.tf_status'] = $sc_status;
        }
        if ($selPlan) {
            $scParams['courses.plan_id'] = $selPlan;
        }
        $adPQuery = $adPQuery->where($adpParams)->andWhere(['<>','tf_status','4']);
        $scQuery= $scQuery->select('courses.*, ap.plan_name')
            ->leftJoin('ad_plans ap','ap.id=`courses`.`plan_id`')
            ->where($scParams)->andWhere(['<>','courses.tf_status','4'])
            ->orderBy('create_at desc');
        $adPage = new Pagination([
            'defaultPageSize' => 10,
            'totalCount' => $adPQuery->count(),
            'pageParam' => 'plan_page',
//            'route' => '/index.php?r=site/ad-manage'
        ]);
        $scPage = new Pagination([
            'defaultPageSize' => 10,
            'totalCount' => $scQuery->count(),
            'pageParam' => 'sucai_page',
//            'route' => '/index.php?r=site/ad-manage'
        ]);
        $totalPlans = AdPlans::find()->where(['user_id'=>$user_id])
            ->andWhere(['<>','tf_status','4'])
            ->asArray()->all();
        $selPlan = count($totalPlans)>0 ? (isset($selPlan) ? $selPlan : $totalPlans[0]['id']) : '';

        $adPlans = $adPQuery->orderBy('create_at desc')
            ->offset($adPage->offset)
            ->limit($adPage->limit)
            ->all();
        $adCourses= $scQuery->orderBy('create_at desc')
            ->offset($scPage->offset)
            ->limit($scPage->limit)
            ->asArray()->all();

        $resPlans = [];
        //$resCourses = [];
        foreach ($adPlans as $v) {
            $item = [];
            foreach ($v as $k=>$value) {
                if ($k != 'properties') {
                    $item[$k] = stripos($value,',') ? preg_replace('/,/i','~',$value) : $value;
                } else {
                    $item[$k] = $value;
                }
                //$item[$k] = stripos($value,',') !==false ? preg_replace('/,/i','~',$value) : $value;
            }
            $resPlans[] = $item;
        }

        $datas = ['planTitles' => Yii::$app->params['planTitles'],
            'courseTitles' => Yii::$app->params['courseTitles'],
            'tf_type_select' => Yii::$app->params['tf_type_select'],
            'tf_status_select' => Yii::$app->params['tf_status_select'],
            'plan_status_select' => Yii::$app->params['plan_status_select'],
            'tf_status' => $tf_status,
            'tf_type' => $tf_type,
            'sc_status' => $sc_status,
            'total_plans' => $totalPlans,
            'sel_plan' => $selPlan,
            'ad_plans' => $resPlans,
            'ad_courses' => $adCourses,
            'sum_plans' => count($adPlans),
            'sum_courses' => count($adCourses)
        ];
        return $this->render('admanage',[
                'data' => $datas,
                'ad_page' => $adPage,
                'sc_page' => $scPage,
            ]);
    }

    public function actionManageSucai(){
        $user_id = Yii::$app->user->id;
        $request = Yii::$app->request;
        $tf_status = $request->get('tf_status');
        $tf_type = $request->get('tf_type');

        $adpParams = ['user_id'=>$user_id];
        $scParams = ['courses.user_id'=>$user_id];

        $adPQuery = AdPlans::find();
        $scQuery = Courses::find();

        if ($tf_status && $tf_status != -1) {
            $adpParams['tf_status'] = $tf_status;
            $scParams['courses.tf_status'] = $tf_status;
        }
        if ($tf_type && $tf_type != -1) {
            $adpParams['tf_type'] = $tf_type;
            $scParams['courses.tf_type'] = $tf_type;
        }
        $adPlans = $adPQuery->where($adpParams)
            ->orderBy('create_at')
            ->all();
        $scQuery= $scQuery->select('courses.*, ap.plan_name')
            ->leftJoin('ad_plans ap','ap.id=`courses`.`plan_id`')
            ->where($scParams)->orderBy('create_at');

        $scPage = new Pagination([
            'defaultPageSize' => 10,
            'totalCount' => $scQuery->count(),
            'pageParam' => 'sucai_page',
        ]);
        $adCourses= $scQuery->orderBy('create_at')
            ->offset($scPage->offset)
            ->limit($scPage->limit)
            ->asArray()->all();

        $datas = ['planTitles' => Yii::$app->params['planTitles'],
            'courseTitles' => Yii::$app->params['courseTitles'],
            'tf_type_select' => Yii::$app->params['tf_type_select'],
            'tf_status_select' => Yii::$app->params['tf_status_select'],
            'tf_status' => $tf_status,
            'tf_type' => $tf_type,
            'ad_plans' => $adPlans,
            'ad_courses' => $adCourses,
            'sum_plans' => count($adPlans),
            'sum_courses' => count($adCourses)
        ];
        return $this->render('managesucai',[
            'data' => $datas,
//            'ad_page' => $adPage,
            'sc_page' => $scPage,
        ]);
    }

    /**
     * 资金管理页面.
     *
     * @return
     */
    public function actionFundManage()
    {
        $user = Yii::$app->user->getIdentity('_identity:yii\web\User:private');
        $request = Yii::$app->request;
        $begin_in = $request->get('begin_in');
        $end_in = $request->get('end_in');
        $begin_out = $request->get('begin_out');
        $end_out = $request->get('end_out');

        $inParams = ['user_id'=>$user->id, 'flow_to'=>'1'];
        $outParams = ['user_id'=>$user->id, 'flow_to'=>'-1'];

        $filterInParams = [];
        $filterOutParams = [];
        if ($begin_in && $end_in){
            if ($begin_in > $end_in) {
                return "日期选择异常";
            }
            $filterInParams = ['between', 'create_at',$begin_in, $end_in];
        }
        if ($begin_out &&  $end_out){
            if ($begin_out > $end_out) {
                return "日期选择异常";
            }
            $filterOutParams = ['between','create_at',$begin_out, $end_out];
        }

        $flowInQuery = FundFlows::find()->where($inParams)->andWhere($filterInParams);
        $flowOutQuery = FundFlows::find()->where($outParams)->andWhere($filterOutParams);
        $flowInPage = new Pagination([
            'defaultPageSize' => 8,
            'totalCount' => $flowInQuery->count(),
            'pageParam' => 'fin_page',
        ]);
        $flowOutPage = new Pagination([
            'defaultPageSize' => 8,
            'totalCount' => $flowOutQuery->count(),
            'pageParam' => 'fin_page',
        ]);
        $total_flow_ins = FundFlows::find()->select('sum(capital) as capital')
            ->where($inParams)->andWhere($filterInParams)
            ->asArray()->one();
        $total_flow_ins = $total_flow_ins['capital'] ?: 0;
        $total_flow_outs = FundFlows::find()->select('sum(capital) as capital')
            ->where($outParams)->andWhere($filterOutParams)
            ->asArray()->one();
        $total_flow_outs = $total_flow_outs['capital'] ?: 0;

        $flowIns = $flowInQuery->offset($flowInPage->offset)
            ->limit($flowInPage->limit)
            ->orderBy('create_at desc')->all();
        $flowOuts = $flowOutQuery->offset($flowOutPage->offset)
            ->limit($flowOutPage->limit)
            ->orderBy('create_at desc')->asArray()->all();

        $data = [
            'avail_fund'=> $user->avail_fund,
            'mobile' => $user->mobile,
            'flow_Ins' => $flowIns,
            'flow_outs' => $flowOuts,
            ];
        return $this->render('fundmanage', [
            'data' => $data,
            'begin_out' => $begin_out,
            'end_out' => $end_out,
            'begin_in' => $begin_in,
            'end_in' => $end_in,
            'total_ins' => $total_flow_ins,
            'total_outs' => $total_flow_outs,
            'in_page' => $flowInPage,
            'out_page' => $flowOutPage
        ]);
    }

    /**
     * 获取数据.
     *
     * @return
     */
    public function actionGetData(){
        $user_id = Yii::$app->user->id;

        $today = date("Y-m-d", mktime());
//        sleep(2);

        //获取账户下当天所有预约数
        $datas = UserData::find()->select(['num'=>'count(id)','time' => 'date_format(create_at ,"%H:00")'])
            ->where(['user_id'=>$user_id, 'date_at'=>$today])
            ->groupBy('time')
            ->orderBy('create_at')
            ->asArray()->all();

        $times = [];
        $nums = [];
        foreach ($datas as $item){
            array_push($times, $item['time']);
            array_push($nums, $item['num']);
        }
        $num = [];
        $time = ["00:00","01:00","02:00","03:00","04:00","05:00","06:00","07:00","08:00","09:00","10:00","11:00","12:00",
            "13:00","14:00","15:00","16:00","17:00","18:00","19:00","20:00","21:00","22:00","23:00","24:00"];
        foreach ($time as $item) {
            array_push($num, (in_array($item, $times) ? array_shift($nums) : 0));
        }
        Yii::$app->response->format=Response::FORMAT_JSON;
        return ['data' => [
            'time' => $time,
            'num' => $num
        ]];
    }

    public function actionErrorPage(){
        $message = Yii::$app->request->get('message');
        return $this->render('error',[
            'message' => $message
        ]);
    }
}
