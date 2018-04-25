<?php

namespace app\controllers;

use app\models\Courses;
use app\models\CourseTags;
use app\models\FundFlows;
use app\models\User;
use Project\Command\YourCustomCommand;
use Yii;
use app\models\AdPlans;
use app\models\AdPlansSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
//use yii\web\Controller;
use app\controllers\LoginCheckController as Controller;
use yii\web\Response;

/**
 * AdPlansController implements the CRUD actions for AdPlans model.
 */
class AdPlansController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all AdPlans models.
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->redirect('index.php?r=site/ad-manage');
    }

    public function actionCoursesByPlan() {
        $request = Yii::$app->request;
        $plan_id = $request->get('plan_id');
        $sucai_id = $request->get('sc_id');
        $params = ['plan_id' => $plan_id];
        $courses = Courses::find()
//            ->select()
            ->where($params)
            ->andWhere(['<>','tf_status','4'])
            ->orderBy('create_at')
            ->asArray()
            ->all();

        if (count($courses)>0) {
            $sucai_id = empty($sucai_id) ? $courses[0]['id'] : $sucai_id;
        }

        return $this->render('planofcourses', [
            'courses' => $courses,
            'sucai_id' => $sucai_id
        ]);
    }
    /*
     * 设置投放计划接口
     */
    public function actionSetTf(){
        $user_id = Yii::$app->user->id;
        $request = Yii::$app->request;
        $plan_id = $request->get('plan_id');
        $is_tf = $request->get('is_tf');

        Yii::$app->response->format=Response::FORMAT_JSON;

        if ($plan_id===null) {
            return [
                'code' => 0,
                'msg' => '投放失败，未找到对应计划，请联系管理员再试'
            ];
        }
        if ($is_tf===null) {
            return [
                'code' => 0,
                'msg' => '投放失败，投放状态未确定'
            ];
        }

//        $userModel = Yii::$app->user->getIdentity("yii\web\User");
//        if ($userModel->avail_fund <= 0) {
//            return [
//                'code' => 0,
//                'msg' => '账户可用资金不足'
//            ];
//        }
        $transaction=Yii::$app->db->beginTransaction();
        try{
            $model = $this->findModel($plan_id);
            $model->tf_status = $is_tf ? '1' : '0';
            $update_at = date("Y-m-d H:i:s", mktime());
            $model->update_at = $update_at;
            if ($model->save()){
                if ($model->tf_status=='0') {
                    Courses::updateAll(['tf_status'=>'2','is_online'=>'0','update_at'=>$update_at],
                        'plan_id='.$plan_id.' and tf_status=1');
                }
                $transaction->commit();
                $msg = $is_tf ? "开启投放成功,请您开始投放素材" : "计划关闭，计划下所有素材关闭成功";
                return [
                    'code'=>1,
                    'msg'=> $msg
                ];
            } else {
                return [
                    'code' => 0,
                    'msg' => '投放失败'
                ];
            }
        }catch (Exception $e) {
            $transaction->rollback();//如果操作失败, 数据回滚
        }
    }


    /**
     * Displays a single AdPlans model.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new AdPlans model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new AdPlans();
        $arrPeriod = [];
        for ($i=0; $i<=24; $i++){
            $arrPeriod[$i] = $i.'时';
        }
        $tags = CourseTags::find()->asArray()->all();
        $ages = [];
        for ($i=16; $i<=45; $i++){
            $ages[$i] = $i.'岁';
        }

        $degrees = [1=>'高中',2=>'专科',3=>'本科',4=>'专硕',5=>'学硕',6=>'博士'];

//        if ($model->load(Yii::$app->request->post()) && $model->save()) {
//            return $this->redirect(['view', 'id' => $model->id]);
//        }

        return $this->render('create', [
            'model' => $model,
            'periods' => $arrPeriod,
            'tags' => $tags,
            'ages' => $ages,
            'degrees' => $degrees
        ]);
    }

    public function actionSave(){
        $user_id = Yii::$app->user->id;
        $request = Yii::$app->request;

        if ($request->isPost) {
            $transaction=Yii::$app->db->beginTransaction();
            try{
                if ($request->post('id')) {
                    $model = $this->findModel($request->post('id'));
                } else {
                    $model = new AdPlans();
                    $model->create_at = date("Y-m-d H:i:s", mktime());
                }

                $tf_date = $request->post('radio_date')=='-1'?'不限':$request->post('tf_date_begin')
                    .','.$request->post('tf_date_end');
                $tf_period = $request->post('radio_time')=='-1'?'不限':$request->post('tf_time_begin')
                    .','.$request->post('tf_time_end');
                $tf_addr = $request->post('radio_addr')=='-1'?'不限':$request->post('opt_addr');
                $age = $request->post('radio_age')=='-1'?'不限':$request->post('opt_age_start')
                    .','.$request->post('opt_age_end');
                $degree = $request->post('radio_degree')=='-1'?'不限':$request->post('opt_degree_start')
                    .','.$request->post('opt_degree_end');
                $tf_value = $request->post('cash');
                $budget = $request->post('budget');
    //            var_dump($tf_date);
    //            var_dump($tf_period);
    //            var_dump($tf_addr);
    //            var_dump($request->post('opt_addr_one'));
    //            var_dump($request->post('opt_addr_many'));

                $model->user_id = $user_id;
                $model->tag_ids = $request->post('opt_tags');
                $model->plan_number = $request->post('plan_name');
                $model->plan_name = $request->post('plan_name');
                $model->tf_status = '2';
                $model->tf_type = $request->post('radio_tf_type');
                $model->tf_value = empty($tf_value) ? 0 : $tf_value;
                $model->budget = empty($budget) ? 0 : $budget;;
                $model->tf_date = $tf_date;
                $model->tf_period = $tf_period;
                $model->properties = $tf_addr;
                $model->age = $age;
                $model->sex = $request->post('radio_gender') ?: '-1';
                $model->degree = $degree;
                $model->update_at = date("Y-m-d H:i:s", mktime());

                if ($model->save()){
                    if ($model->tf_status == 1) {
//                        $userModel = Yii::$app->user->getIdentity("yii\web\User");
//                        $userModel->avail_fund -= $model->budget;
//                        $fundFlowModel = new FundFlows();
//                        $fundFlowModel->user_id = $user_id;
//                        $fundFlowModel->create_at = date("Y-m-d H:i:s", mktime());;
//                        $fundFlowModel->capital = $model->budget;
//                        $fundFlowModel->flow_to = '-1';
//                        if (!$fundFlowModel->save()){
//                            var_dump($fundFlowModel->errors);
//                            return "fundflow新增错误";
//                        }
//                        if (!$userModel->save()) {
//                            var_dump($userModel->errors);
//                            return "user更改错误";
//                        }
                    }
                    $transaction->commit();//提交事务会真正的执行数据库操作
                    if ($request->post('is_redirect') == 1) {
                        return $this->redirect('/index.php?r=courses/create&plan_id='.$model->id);
                    } else {
                        return $this->redirect('/index.php?r=ad-plans/');
                    }
                } else {
                    var_dump($model->errors);
                    return "新建计划错误";
                }
            }catch (Exception $e) {
                $transaction->rollback();//如果操作失败, 数据回滚
            }
        }

    }

    /**
     * Updates an existing AdPlans model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $arrPeriod = [];
        for ($i=0; $i<=24; $i++){
            $arrPeriod[$i] = $i.'时';
        }
        $tags = CourseTags::find()->asArray()->all();
        $ages = [];
        for ($i=16; $i<=45; $i++){
            $ages[$i] = $i.'岁';
        }

        $degrees = [1=>'高中',2=>'专科',3=>'本科',4=>'专硕',5=>'学硕',6=>'博士'];

        return $this->render('create', [
            'model' => $model,
            'periods' => $arrPeriod,
            'tags' => $tags,
            'ages' => $ages,
            'degrees' => $degrees
        ]);
    }

    /**
     * Deletes an existing AdPlans model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
//        $this->findModel($id)->delete();
        $transaction=Yii::$app->db->beginTransaction();
        try{
            $model = $this->findModel($id);
            $model->tf_status = '4';
            $update_at = date("Y-m-d H:i:s", mktime());
            if ($model->save()){
                $courses = Courses::updateAll(['tf_status'=>'4','update_at'=>$update_at],'plan_id='.$id);

                $transaction->commit();
                return $this->redirect('/index.php?r=site/ad-manage#plan');
            }
        }catch (Exception $e) {
            $transaction->rollback();//如果操作失败, 数据回滚
        }
    }

    /**
     * Finds the AdPlans model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return AdPlans the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AdPlans::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
