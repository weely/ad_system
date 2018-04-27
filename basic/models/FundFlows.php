<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "fund_flows".
 *
 * @property string $id
 * @property string $user_id
 * @property double $capital 流动资金值
 * @property string $flow_to 资金流向
 * @property string $create_at
 * @property string $update_at
 */
class FundFlows extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'fund_flows';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'flow_to'], 'required'],
            [['user_id'], 'integer'],
            [['plan_id'], 'integer'],
            [['capital'], 'number'],
            [['flow_to'], 'string'],
            [['create_at', 'update_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User_ID',
            'plan_id' => 'Plan_ID',
            'capital' => 'Capital',
            'flow_to' => 'Flow_To',
            'create_at' => 'Create_At',
            'update_at' => 'Update_At',
        ];
    }
}
