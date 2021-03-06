<?php

namespace app\controllers;

use app\models\AdPlans;
use app\models\CourseLabInfos;
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

    public function actionSetTf(){
        $user_id = Yii::$app->user->id;
        $request = Yii::$app->request;
        $course_id = $request->get('course_id');
        $is_tf = $request->get('is_tf');

        Yii::$app->response->format=Response::FORMAT_JSON;

        if ($course_id===null) {
            return [
                'code' => 0,
                'msg' => '投放失败，未找到对应素材，请联系管理员再试'
            ];
        }
        if ($is_tf===null) {
            return [
                'code' => 0,
                'msg' => '投放失败，投放状态未确定'
            ];
        }
        $model = $this->findModel($course_id);
        if (!in_array($model->tf_status,['1','2'])) {
            return [
                'code' => 0,
                'msg' => "素材暂未审核通过，请先审核完成再投放"
            ];
        }
        $model->tf_status = $is_tf;

        $model->update_at = date("Y-m-d H:i:s", mktime());
        if ($model->save()) {
            $msg = $is_tf ? "素材投放成功" : "素材关闭成功";
            return [
                'code' => 1,
                'msg' => $msg
            ];
        } else {
            return [
                'code' => 0,
                'msg' => '素材投放失败'
            ];
        }
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
                ->andWhere(['<>','tf_status','4'])
                ->asArray()
                ->all();
        }
        if (!isset($plans) || count($plans)<=0) {
            $plans = AdPlans::find()
                ->select('id,plan_number,plan_name,properties')
                ->where($params)
                ->andWhere(['<>','tf_status','4'])
                ->asArray()
                ->all();
        }

        if (count($plans)<=0) {
            return $this->render('error',['message'=> '！该账户下无广告计划，请先创建广告计划']);
        }

        $model = new Courses();

        $course_lab_info = [
            'lab_1' => "价值",
            'lab_2' => "288",
            'lab_3' => "元",
            'lab_4' => "咨询会",
            'lab_5' => "立即0元抢",
            'lab_6' => "您好",
            'lab_7' => "，您已成功获得咨询会礼包,学校老师会通过",
            'lab_8' => "联系您",
        ];

        //$model = $model->find()->where(['id'=>$model->id])->asArray()->all();
        return $this->render('create', [
            'model' => $model,
            'plans' => $plans,
            'course_labels' => $course_lab_info
        ]);
    }

    public function actionSave()
    {
        $user_id = Yii::$app->user->id;
        $params = ['user_id' => $user_id];
        $request = Yii::$app->request;


        $transaction = Yii::$app->db->beginTransaction();
        try {
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
                $planModel = AdPlans::find()->where(['id' => $request->post('plan_id')])->one();

                $model->user_id = $user_id;
                $model->plan_id = $planModel->id;
                $model->tf_status = 0;
                $model->tf_type = $planModel->tf_type;
                //$model->tf_value = 0;
                $model->is_online = $request->post('is_online') ?: '0';
                $model->is_h5 = $request->post('is_h5') ?: '0';
                $model->title_img = $request->post('title_img') ?: '';
                $model->ad_sc_title = $request->post('sc_title');
                $model->tag_ids = $planModel->tag_ids;
                $model->logo = $request->post('logo') ?: '';
                $model->is_link = $request->post('h5') ?: '0';
                $model->img_html = $request->post('img_html') ?: '';
                $model->properties = $request->post('properties') ?: '';
                $model->tags = $request->post('tags') ?: '';
                $model->update_at = date("Y-m-d H:i:s", mktime());
                if ($model->save()) {
                    if ($model->tf_type == '4') {
                        if ($model->id) {
                            $courseLabInfo = CourseLabInfos::find()
                                ->where(['course_id' => $model->id])->one();
                            if (!$courseLabInfo) {
                                $courseLabInfo = new CourseLabInfos();
                                $courseLabInfo->course_id = $model->id;
                            }
                        } else {
                            $courseLabInfo = new CourseLabInfos();
                            $courseLabInfo->course_id = $model->id;
                        }

                        $lab1 = $request->post('btn_7');
                        $lab2 = $request->post('btn_4');
                        $lab3 = $request->post('btn_8');
                        $lab4 = $request->post('btn_5');
                        $lab5 = $request->post('btn_6');
                        $lab6 = $request->post('btn_1');
                        $lab7 = $request->post('btn_2');
                        $lab8 = $request->post('btn_3');

                        $courseLabInfo->lab_1 = $lab1;
                        $courseLabInfo->lab_2 = $lab2;
                        $courseLabInfo->lab_3 = $lab3;
                        $courseLabInfo->lab_4 = $lab4;
                        $courseLabInfo->lab_5 = $lab5;
                        $courseLabInfo->lab_6 = $lab6;
                        $courseLabInfo->lab_7 = $lab7;
                        $courseLabInfo->lab_8 = $lab8;

                        if (!$courseLabInfo->save()) {
                            throw new \Exception('点击按钮信息保存异常！');
                        }
                    }
                    $transaction->commit();

                    return $this->redirect('/index.php?r=courses/check-page');
                } else {
                    throw new \Exception($model->errors());
                }
            }
        } catch (Exception $e) {
            $transaction->rollback();//如果操作失败, 数据回滚
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

            $user_path = $_SERVER['DOCUMENT_ROOT']."/uploads/".$username;
            if (!file_exists($user_path)) {
                mkdir($user_path);
            }
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
            // $move_to_file = $user_path."/".$file_name;
            // $move_to_file = $user_path."/".time().rand(1,1000).substr($file_name,strrpos($file_name,"."));
            $move_to_file = $user_path."/".time().rand(1,1000).".".$file_name;

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
            ->select('id,plan_number,plan_name,properties')
            ->where($params)
            ->andWhere(['<>','tf_status','4'])
            ->asArray()
            ->all();

        if ($model->tf_type == '4') {
            $course_lab_info = CourseLabInfos::find()->where(['course_id'=>$model->id])->asArray()->one();
            if (!$course_lab_info) {
                $course_lab_info = [
                    'lab_1' => "价值",
                    'lab_2' => "288",
                    'lab_3' => "元",
                    'lab_4' => "咨询会",
                    'lab_5' => "立即0元抢",
                    'lab_6' => "您好",
                    'lab_7' => "，您已成功获得咨询会礼包,学校老师会通过",
                    'lab_8' => "联系您",
                ];
            }
        } else {
            $course_lab_info = [];
        }

        return $this->render('create', [
            'model' => $model,
            'plans' => $plans,
            'course_labels' => $course_lab_info
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
        $model = $this->findModel($id);
        $model->tf_status = '4';
        $model->update_at = date("Y-m-d H:i:s", mktime());
        if ($model->save()) {
            return $this->redirect('/index.php?r=site/ad-manage#sucai');
        }
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
