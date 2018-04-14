<?php

namespace app\controllers;

use app\models\AdPlans;
use Yii;
use app\models\Courses;
use app\models\CoursesSearch;
//use yii\web\Controller;
use app\controllers\LoginCheckController as controller;
use yii\web\Response;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
//use app\models\UploadForm;
//use yii\web\UploadedFile;

/**
 * CoursesController implements the CRUD actions for Courses model.
 */
class CoursesController extends Controller
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
     * Lists all Courses models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CoursesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Courses model.
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
     * Creates a new Courses model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $user_id = Yii::$app->user->id;
        $request = Yii::$app->request;
        $params = ['user_id' => $user_id];

        if ($request->get('plan_id')) {
            $plans = AdPlans::find()
                ->where(['id'=>$request->get('plan_id')])
                ->asArray()
                ->all();
        }
        if (!isset($plans) || count($plans)<=0) {
            $plans = AdPlans::find()
                ->select('id,old_plan_id,plan_number,plan_name,properties')
                ->where($user_id)
                ->asArray()
                ->all();
        }

        if (count($plans)<=0) {
            return '!请先创建广告计划';
        }

        $model = new Courses();
//        $model = $model->find()->where(['id'=>$model->id])->asArray()->all();
//        $model = $model->findAll($model);
        return $this->render('create', [
            'model' => $model,
            'plans' => $plans
        ]);
    }

    public function actionSave()
    {
        $user_id = Yii::$app->user->id;
        $params = ['user_id' => $user_id];
        $request = Yii::$app->request;
        if ($request->isPost) {
            if ($request->post('id')) {
                $model = $this->findModel($request->post('id'));
            } else {
                $model = new Courses();
                $model->create_at = date("Y-m-d H:i:s", mktime());
            }

//  广告标题图片       title_img
//  广告特点           type_tags
//  广告LOGO          logo
//  富文本编辑框       edit_html
//  HTML源文件        img_html
            $model->user_id = $user_id;
            $model->plan_id = $request->post('plan_id');
            $model->tf_status = 0;
//            $model->tf_type = 0;
//            $model->tf_value = 0;
            $model->is_online = $request->post('is_online') ?: '0';
            $model->is_h5 = $request->post('is_h5') ?: '0';
            $model->title_img = $request->post('title_img') ?: '';
            $model->ad_sc_title = $request->post('sc_title');
            $model->ad_type = '';
            $model->tag_ids = '';
            $model->logo = $request->post('logo') ?: '';

//            var_dump($request->post('is_h5'));
//            return;

            $model->img_html = $request->post('img_html') ?: '';
            $model->properties = $request->post('properties') ?: '';
            $model->tags = $request->post('tags') ?: '';
            $model->logo = $request->post('logo') ?: '';

            if ($model->save()){
                return $this->redirect('/index.php?r=courses/check-page');
            } else {
                var_dump($model->errors);
//                return $this->render('error',[]);
            }
        }
    }

    public function actionCheckPage(){
        return $this->render('checkpage',[
            'msg'=> '提交成功'
        ]);
    }

    public function actionUploadFile(){
        $username = Yii::$app->user->getIdentity('"_identity":"yii\web\User"')['username'];
        $request = Yii::$app->request;
        Yii::$app->response->format=Response::FORMAT_JSON;
        if ($request->isPost) {
            //支持上传图片格式
//            var_dump($_FILES);
            $allowType = array('jpg','jpeg','png');
            $file_size = $_FILES['file']['size'];
            $file_name = $_FILES['file']['name'];
            $uploaded_file = $_FILES['file']['tmp_name'];
            $type = strtolower(substr($file_name,strrpos($file_name,'.')+1));
            if (!in_array($type, $allowType)) {
                return [
                    'status'=>'error',
                    'msg'=> '错误!请输入正确的文件格式'
                ];
            }
            if ($file_size>3*1024*1024) {
                return [
                    'status'=>'error',
                    'msg'=> '错误!传输文件过大'
                ];
            }
//            $file_name = strstr($file_name,'.') ? $file_name : ($file_name.'.'.$type);
//            $user_path_show = "/uploads/".$username;
            $user_path = $_SERVER['DOCUMENT_ROOT']."/uploads/".$username;
            if (strtolower($request->post('file_name')) == 'logo') {
                $user_path .= '/logo';
            }
            if (strtolower($request->post('file_name')) == 'title') {
                $user_path .= '/title';
            }
            if (strtolower($request->post('file_name')) == 'h5') {
                $user_path .= '/h5';
            }
//            $file_path = strtolower(trim(($path.$file_name)));
            if (!file_exists($user_path)) {
                mkdir($user_path);
            }
//            $move_to_file = $user_path."/".time().rand(1,1000).substr($file_name,strrpos($file_name,"."));
//            $move_to_file = $user_path."/".time().rand(1,1000).".".$file_name;
            $move_to_file = $user_path."/".$file_name;

            if(move_uploaded_file($uploaded_file,iconv("utf-8","gb2312",$move_to_file))) {
                return [
                    'status' => 'true',
                    'msg' => substr($move_to_file,strlen($_SERVER['DOCUMENT_ROOT']))
                ];
            } else {
                return [
                    'status'=>'error',
                    'msg'=> '上传失败'
                ];
            }
        }
    }

    /**
     * Updates an existing Courses model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $user_id = Yii::$app->user->id;
        $params = ['user_id' => $user_id];

        $model = $this->findModel($id);
        $params['id'] = $model->plan_id;
        $plans = AdPlans::find()
            ->select('id,old_plan_id,plan_number,plan_name,properties')
            ->where($params)
            ->asArray()
            ->all();

        return $this->render('create', [
            'model' => $model,
            'plans' => $plans
        ]);
    }

    /**
     * Deletes an existing Courses model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
//        $this->findModel($id)->delete();

        return $this->redirect('/index.php?r=site/ad-manage#sucai');
    }

    /**
     * Finds the Courses model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Courses the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Courses::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
