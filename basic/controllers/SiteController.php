<?php

namespace app\controllers;

use app\models\FundFlows;
use app\models\HisUserData;
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

//        var_dump($user_id);
//        return ;
//        $page_size = isset($request->page_size) ? $request->page_size : 10;
//        $page_num = isset($request->page_num) ? $request->page_num : 1;

        $plan_id = $request->get('plan_id');
        $c_id = $request->get('c_id');
        $order_value = $request->get('order_value') ?: 'date_at';
        //1-> asc, 0->desc;
        $order_type = $request->get('order_value') && $request->get('order_value')==1  ? 'ASC' : 'DESC';

        $apQuery = AdPlans::find();
        $cQuery = Courses::find();
        /*参数字段*/
        $aPparams = ['user_id'=>$user_id];              //广告计划查询参数
        $cParams = ['user_id'=>$user_id];               //广告素材查询参数
        $tdParams = ['user_id' => $user_id];            //当天数据查询参数
        $hdParams = ['user_id' => $user_id];            //历史数据查询参数
        $tgpParams = 'user_data.user_id';
        //$htgpParams = '';              //历史数据分组参数

        /*查询数据*/
        $userPlans = $apQuery->where($aPparams)->andWhere(['<>','tf_status','4'])->orderBy('id')->asArray()->all();
        $userCourses = $cQuery->where($cParams)->andWhere(['<>','courses.tf_status','4'])->orderBy('id')->asArray()->all();

        if (!$plan_id && !$c_id){
            $his_sql_sel = 'his_user_data.user_id as id,';
        }
        if ($plan_id) {
            $aPparams['id'] = $plan_id;
            $cParams['plan_id'] = $plan_id;
            $tdParams['plan_id'] = $plan_id;
            $hdParams['plan_id'] = $plan_id;
            $tgpParams = $tgpParams. ',user_data.plan_id';
        //    $htgpParams = $htgpParams. ',his_user_data.plan_id';
            $htgpParams = 'his_user_data.plan_id';

            $his_sql_sel = 'his_user_data.plan_id as id,';
            $htgpParams = "his_user_data.plan_id";
        }
        if ($c_id) {
            $cParams['id'] = $c_id;
            $tdParams['course_id'] = $c_id;
            $hdParams['course_id'] = $c_id;
            $tgpParams = $tgpParams. ',user_data.course_id';
        //    $htgpParams = $htgpParams. ',his_user_data.course_id';
            $htgpParams = 'his_user_data.course_id';

            $his_sql_sel = 'his_user_data.course_id as id,';
            $htgpParams = "his_user_data.course_id";
        }

        $his_sql_sel = 'his_user_data.user_id as id,sum(h.show) as show_num, sum(h.view) as click_num,sum(h.book) as book_num,
                his_user_data.date_at';
        $htgpParams = 'his_user_data.date_at';

        $choosePlans = $apQuery->where($aPparams)->orderBy('id')->all();
        $chooseCourses = $cQuery->where($cParams)->orderBy('id')->all();
        //投放状态： 0-> 待审核  ，1->投放中，  2->待投放
        $totalNumber = count($chooseCourses);
        $cParams['tf_status'] = '1';
        $tfz = count($cQuery->where($cParams)->all());
        $cParams['tf_status'] = '2';
        $dtf = count($cQuery->where($cParams)->all());
        $cParams['tf_status'] = '0';
        $dsk = count($cQuery->where($cParams)->all());

        $todayDatas = UserData::find()
            ->select('user_data.id,sum(h.show) as "show_num", sum(h.view) as click_num,sum(h.book) as book_num,
                user_data.date_at')
            ->leftJoin('hours as h', 'h.`cid`=`user_data`.`course_id`')
            ->where($tdParams)
            ->groupBy($tgpParams)
            ->orderBy('create_at')
            ->asArray()->all();

        $dataQuery = HisUserData::find()
            ->select($his_sql_sel)
            ->leftJoin('hours as h', 'h.`cid`=`his_user_data`.`course_id` and his_user_data.date_at=date(h.time)')
            ->where($hdParams)
            ->groupBy($htgpParams);
        $page = new Pagination([
            'defaultPageSize' => 10,
            'totalCount' => $dataQuery->count(),
        ]);

        $hisDatas = $dataQuery->orderBy($order_value, $order_type)
            ->offset($page->offset)->limit($page->limit)
            ->asArray()->all();

        $sumClick = 0;
        $sumbook = 0;
        $sumshow = 0;
        $sumClickRate = 0;
        $sumBookRate = 0;
        $resHisData = [];
        foreach ($hisDatas as $hisData) {
            $hisData['click_rate'] = round(($hisData['show_num']>0 ? $hisData['click_num'] / $hisData['show_num'] : 0),4)*100 . '%';
            $hisData['book_rate'] = round(($hisData['show_num']>0 ? $hisData['book_num'] / $hisData['show_num'] : 0),4)*100 .'% ';
            // TODO 日消耗暂时未计算
            $hisData['day_cost'] = 0;
            $sumshow +=  $hisData['show_num'];
            $sumbook +=  $hisData['book_num'];
            $sumClick +=  $hisData['click_num'];
            $resHisData[] = $hisData;
        }
        $sumClickRate = round(($sumshow>0 ? $sumClick/$sumshow : 0),4)*100 . '%';
        $sumBookRate = round(($sumshow>0 ? $sumbook/$sumshow : 0),4)*100 . '%';
        // TODO 日消耗暂时未计算
        $sumDatas = ['总计', $sumshow, $sumClick, $sumClickRate, $sumbook, $sumBookRate, 0];

        $c_id = count($userCourses)>0 ? (isset($c_id) ? $c_id : $userCourses[0]['id']) : null;
        $plan_id = count($userPlans)>0 ? (isset($plan_id) ? $plan_id : $userPlans[0]['id']) : null;

        $datas = [
            'tableTitles' => [
                '日期','曝光数','点击数','点击率','预约数','预约率','日消耗(元)'
            ],
            'tfz' => $tfz,
            'dtf' => $dtf,
            'dsk' => $dsk,
            'today_data' => $todayDatas,
            'his_data' => $resHisData,
            'sum_data' => $sumDatas
        ];
        return $this->render('index',[
            'plans' => $userPlans,
            'sel_plan' => $plan_id,
            'sel_course' => $c_id,
            'courses' => $userCourses,
            'data' => $datas,
            'page' => $page
        ]);
    }

    public function actionUserDatas() {
        $user_id = Yii::$app->user->id;
        $request = Yii::$app->request;

        $page_size = isset($request->page_size) ? $request->page_size : 10;
        $page_num = isset($request->page_num) ? $request->page_num : 1;
//        var_dump($page_size);
//        return;


        $plan_id = $request->get('plan_id');
        $c_id = $request->get('c_id');

        $begin_time = $request->get('begin_time');
        $end_time = $request->get('end_time');

        $order_value = $request->get('order_value') ?: 'date_at';
        //1-> asc, 0->desc;
        $order_type = $request->get('order_value') && $request->get('order_value')==1  ? 'ASC' : 'DESC';
        $apQuery = AdPlans::find();
        $cQuery = Courses::find();
        /*参数字段*/
        $aPparams = ['user_id'=>$user_id];              //广告计划查询参数
        $cParams = ['user_id'=>$user_id];               //广告素材查询参数
        $tdParams = ['user_id' => $user_id];            //当天数据查询参数
        $hdParams = ['user_id' => $user_id];            //历史数据查询参数
        $tgpParams = 'user_data.user_id';
        $htgpParams = '';              //历史数据分组参数

        if (!$plan_id && !$c_id){
            $his_sql_sel = 'his_user_data.user_id as id,';
        }
        if ($plan_id) {
            $aPparams['id'] = $plan_id;
            $cParams['plan_id'] = $plan_id;
            $tdParams['plan_id'] = $plan_id;
            $hdParams['plan_id'] = $plan_id;
            $tgpParams = $tgpParams. ',user_data.plan_id';
//            $htgpParams = $htgpParams. ',his_user_data.plan_id';
            $htgpParams = 'his_user_data.plan_id';

            $his_sql_sel = 'his_user_data.plan_id as id,';
            $htgpParams = "his_user_data.plan_id";
        }
        if ($c_id) {
            $cParams['id'] = $c_id;
            $tdParams['course_id'] = $c_id;
            $hdParams['course_id'] = $c_id;
            $tgpParams = $tgpParams. ',user_data.course_id';
//            $htgpParams = $htgpParams. ',his_user_data.course_id';
            $htgpParams = 'his_user_data.course_id';

            $his_sql_sel = 'his_user_data.course_id as id,';
            $htgpParams = "his_user_data.course_id";
        }

        $his_sql_sel .= ',sum(h.show) as show_num, sum(h.view) as click_num,sum(h.book) as book_num,
                his_user_data.date_at';
        $htgpParams .= ',his_user_data.date_at';

        $choosePlans = $apQuery->where($aPparams)->orderBy('id')->all();
        $chooseCourses = $cQuery->where($cParams)->orderBy('id')->all();

        $todayDatas = UserData::find()
            ->select('user_data.id,sum(h.show) as "show_num", sum(h.view) as click_num,sum(h.book) as book_num,
                user_data.date_at')
            ->leftJoin('hours as h', 'h.`cid`=`user_data`.`course_id`')
            ->where($tdParams)
            ->groupBy($tgpParams)
            ->orderBy('create_at')
            ->asArray()
            ->all();

//        $page = new Pagination(['totalCount' => ]);
//        $hisDatas = HisUserData::find()
//            ->select($his_sql_sel)
//            ->leftJoin('hours as h', 'h.`cid`=`his_user_data`.`course_id` and his_user_data.date_at=date(h.time)')
//            ->where($hdParams)
//            ->groupBy($htgpParams)
//            ->orderBy($order_value, $order_type)
//            ->asArray()
//            ->all();
        $totalCount = HisUserData::find()
            ->select($his_sql_sel)
            ->leftJoin('hours as h', 'h.`cid`=`his_user_data`.`course_id` and his_user_data.date_at=date(h.time)')
            ->where($hdParams)
            ->groupBy($htgpParams)->count();
        $page_num = max(min(ceil($totalCount/$page_size), $page_num),1);
        $hisDatas = HisUserData::find()
            ->select($his_sql_sel)
            ->leftJoin('hours as h', 'h.`cid`=`his_user_data`.`course_id` and his_user_data.date_at=date(h.time)')
            ->where($hdParams)
            ->groupBy($htgpParams)
            ->orderBy($order_value, $order_type)
            ->limit($page_size)
            ->offset(($page_num-1)*$page_size)
            ->asArray()
            ->all();

        //var_dump($hisDatas);
        //return;

        $sumClick = 0;
        $sumbook = 0;
        $sumshow = 0;
        $sumClickRate = 0;
        $sumBookRate = 0;
        $resHisData = [];
        foreach ($hisDatas as $hisData) {
            $hisData['click_rate'] = round(($hisData['show_num']>0 ? $hisData['click_num'] / $hisData['show_num'] : 0),4)*100 . '%';
            $hisData['book_rate'] = round(($hisData['show_num']>0 ? $hisData['book_num'] / $hisData['show_num'] : 0),4)*100 .'% ';
            // TODO 日消耗暂时未计算
            $hisData['day_cost'] = 0;
            $sumshow +=  $hisData['show_num'];
            $sumbook +=  $hisData['book_num'];
            $sumClick +=  $hisData['click_num'];
            $resHisData[] = $hisData;
        }
        $sumClickRate = round(($sumshow>0 ? $sumClick/$sumshow : 0),4)*100 . '%';
        $sumBookRate = round(($sumshow>0 ? $sumbook/$sumshow : 0),4)*100 . '%';
        // TODO 日消耗暂时未计算
        $sumDatas = ['总计', $sumshow, $sumClick, $sumClickRate, $sumbook, $sumBookRate, 0];

        $datas = [
            'today_data' => $todayDatas,
            'his_data' => $resHisData,
            'sum_data' => $sumDatas,
            'page_num' => $page_num,
            'page_size' => ceil($totalCount/$page_size)
        ];
        Yii::$app->response->format=Response::FORMAT_JSON;
        return ['data' => $datas];
    }


    /**
     * Login action.
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
        $params = ['user_id'=>$user_id];
        $hisParams = ['user_id'=>$user_id];
        if ($req_year) {
            $params["DATE_FORMAT(`date_at`, '%Y')"] = $req_year;
            $hisParams["DATE_FORMAT(`date_at`, '%Y')"] = $req_year;
        }

        $todayDatas = UserData::find()->select('count(*) as nums,date_at')->where($params)
            ->groupBy('date_at,course_id')
            ->asArray()
            ->all();

        $hisDatas = HisUserData::find()->select('count(*) as nums,date_at')->where($hisParams)
            ->groupBy('date_at,course_id')
            ->asArray()
            ->all();
        $datas = array_merge($hisDatas,$todayDatas);
        $resData = [];


        foreach ($datas as $data){
            $resData[$data['date_at']] = $data['nums'];
        }

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
            ->orderBy('create_at');
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

        $adPlans = $adPQuery->orderBy('create_at')
            ->offset($adPage->offset)
            ->limit($adPage->limit)
            ->all();
        $adCourses= $scQuery->orderBy('create_at')
            ->offset($scPage->offset)
            ->limit($scPage->limit)
            ->asArray()->all();

        $resPlans = [];
        //$resCourses = [];
        foreach ($adPlans as $v) {
            $item = [];
            foreach ($v as $k=>$value) {
//                if ($k == 'tf_date') {
//                    $item[$k] = strstr($value,',') ? preg_replace(',','-',$value) : $value;
//                } else {
//                    $item[$k] = $value;
//                }

                $item[$k] = stripos($value,',') !==false ? preg_replace('/,/i','~',$value) : $value;
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
            $filterInParams = ['between', 'create_at',$begin_in, $end_in];
        }
        if ($begin_out &&  $end_out){
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
            ->orderBy('create_at', 'desc')->all();
        $flowOuts = $flowOutQuery->offset($flowOutPage->offset)
            ->limit($flowOutPage->limit)
            ->orderBy('create_at')->all();

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

//        sleep(2);

        //获取账户下当天所有预约数
        $datas = UserData::find()->select(['num'=>'count(id)','time' => 'date_format(create_at ,"%H:00")'])
            ->where(['user_id'=>$user_id])
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
