<?php
/**
 * Created by PhpStorm.
 * User: Lw
 * Date: 2018/4/21
 * Time: 22:12
 */

namespace app\commands;

use app\models\AdPlans;
use app\models\HisUserData;
use app\models\User;
use Yii;
use yii\base\Model;
use yii\console\Controller;
use yii\console\ExitCode;

use app\models\UserData;
use yii\db\Exception;


class TransferController extends Controller
{
    public function actionIndex()
    {
        $date_at = date("Y-m-d", mktime());

        $transaction=Yii::$app->db->beginTransaction();
        try{
            $params = ['<', 'date_at', $date_at];
            $data = UserData::find()->where($params)->all();
            echo $date_at;
            Yii::$app->db->createCommand()
                ->batchInsert(HisUserData::tableName(),['data_id','user_id','plan_id','course_id','info_name',
                    'info_gender','info_mobile','info_company', 'info_position','info_city', 'info_tags',
                    'flag','state','create_at','date_at'], $data)
                ->execute();

            $dataByPlan = Model::find()
                ->select('p.id, p.tf_type, user_data.*')
                ->leftJoin('ad_plans as p','p.id=user_data.plan_id')
                ->where()
                ->groupBy('')
                ->all();

            //更新账户资金
//            foreach ($data as $d) {
//            }

            var_dump($dataByPlan);
            return;

            UserData::deleteAll($params);
            $transaction->commit();
            Yii::info('数据转移成功。');
            echo $date_at."数据转移成功";
        } catch (Exception $e){
            echo "转移当日数据失败\r\n";
            Yii::error($e->getName().$e->getFile().$e->getMessage());
            $transaction->rollback();
        }
        return ExitCode::OK;
    }
}