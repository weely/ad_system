<?php
/**
 * Created by PhpStorm.
 * User: Lw
 * Date: 2018/4/21
 * Time: 22:12
 */

namespace app\commands;

use app\models\AdPlans;
use app\models\FundFlows;
use app\models\User;
use Yii;
use yii\base\Model;
use yii\console\Controller;
use yii\console\ExitCode;
use app\models\UserData;
use yii\db\Exception;
use Dr;

class SettlementController extends Controller
{
    public function actionIndex()
    {
        $date_at = date("Y-m-d", mktime());
        $transaction=Yii::$app->db->beginTransaction();
        try{
            $params = ['ud.date_at' => $date_at, 'is_settlement'=>'0'];
            $datas = User::find()
                ->select('user.id,count(ud.id) as num,p.id as plan_id, p.tf_type ,p.tf_value, ud.state')
                ->leftJoin('ad_plans as p','p.user_id=user.id and p.tf_status<>4')
                ->leftJoin('user_data as ud', 'ud.plan_id=p.id and ud.user_id=user.id')
                ->where($params)
                ->groupBy('p.id, ud.state')
                ->asArray()
                ->all();

            $data = [];

            echo "开始结算\r\n";
            //更新账户资金,记录资金流水
            foreach ($datas as $item) {
                $d = [];

                $d['user_id'] = $item['id'];
                $d['plan_id'] = $item['plan_id'];

                //不同出价方式不同的处理结算方式
                if ($item['tf_type'] == '1') {
                    $d['capital'] = $item['num'] * $item['tf_value'];
                }
                if ($item['tf_type'] == '2'){
                    $d['capital'] = $item['num'] * $item['tf_value'];
                }
                if ($item['tf_type'] == '3'){
                    if ($item['state'] == '1') { //以预约计费模式
                        continue;
                    } else if ($item['state'] == '2'){ //以点击计费模式
                        $d['capital'] = $item['num'] * $item['tf_value'];
                    }
                }
                if ($item['tf_type'] == '4'){
                    if ($item['state'] == '1') {  //以预约计费模式
                        $d['capital'] = $item['num'] * $item['tf_value'];
                    } else if ($item['state'] == '2'){ //以点击计费模式
                        continue;
                    }
                }
                if (!isset($d['capital'])){
                    continue;
                }

                $d['flow_to'] = '-1';
                $d['create_at'] = date('Y-m-d', mktime());
                $d['update_at'] = date('Y-m-d', mktime());
                $data[] = $d;
                $user = User::find()->where(['id'=>$item['id']])->one();
                $user->avail_fund -= $d['capital'];
                $user->update_at = $date_at;

                if ($user->save()){
                    $settlementParam = 'user_id='.$item['id']." and is_settlement='0' and date_at='". $date_at ."'";
                    UserData::updateAll(['is_settlement'=>'1'], $settlementParam);
                    //echo UserData::getDb()->createCommand()->logQuery();
                }
            }
            //print_r($data);

            $query = Yii::$app->db->createCommand()
                ->batchInsert(FundFlows::tableName(),['user_id','plan_id','capital','flow_to','create_at','update_at'], $data);
            $query->execute();
            echo $query->getSql();

            $transaction->commit();
            Yii::info('结算成功');
            echo $date_at."结算成功";
        } catch (Exception $e){
            echo "结算当日数据失败\r\n";
            echo $e->getMessage();
            Yii::error($e->getName().$e->getFile().$e->getMessage());
            $transaction->rollback();
        }
        return ExitCode::OK;
    }
}