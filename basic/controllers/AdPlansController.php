<?php

namespace app\controllers;

use app\models\Courses;
use app\models\CourseTags;
use Project\Command\YourCustomCommand;
use Yii;
use app\models\AdPlans;
use app\models\AdPlansSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
//use yii\web\Controller;
use app\controllers\LoginCheckController as Controller;
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

//            var_dump($request->post('id'));


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
            $model->plan_number = $request->post('plan_num');
            $model->plan_name = $request->post('plan_name');
//            $model->tf_status = $request->post('tf_status');
            $model->tf_status = '2';
            $model->tf_type = $request->post('radio_tf_type');
            $model->tf_value = empty($tf_value) ? 0 : $tf_value;
            $model->budget = empty($budget) ? 0 : $budget;;
            $model->tf_date = $tf_date;
            $model->tf_period = $tf_period;
            $model->properties = $tf_addr;
            $model->age = $age;
            $model->sex = $request->post('radio_gender');
            $model->degree = $degree;

            if ($model->save()){
                if ($request->post('is_redirect') == 1) {
                    return $this->redirect('/index.php?r=courses/create&plan_id='.$model->id);
                } else {
                    return $this->redirect('/index.php?r=ad-plans/');
                }
            } else {
                return 'fail';
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

        return $this->redirect('/index.php?r=site/ad-manage#plan');
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
