<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ad_plans".
 *
 * @property string $id 主键id
 * @property string $old_plan_id 旧版本id
 * @property string $user_id 广告主id
 * @property string $tag_ids 课程标签id
 * @property string $plan_number 计划编号
 * @property string $plan_name 计划名称
 * @property string $tf_status 状态：1.开启;0.关闭;4.删除
 * @property string $tf_type 投放模式：1：CPM, 2: CPC, 3: CPA,4:CPL,5:CPS
 * @property int $tf_value 投放值
 * @property double $budget 每日预算
 * @property string $tf_date 投放日期
 * @property string $tf_period 投放时段
 * @property string $properties 投放区域
 * @property string $age 年龄约束
 * @property string $sex 性别约束: 1、男，-1、女,0、不限
 * @property string $degree 学历约束
 * @property string $cpm
 * @property string $cpc
 * @property string $cps
 * @property string $create_at 计划生成时间
 * @property string $update_at 计划更新时间
 */
class AdPlans extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ad_plans';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['old_plan_id', 'user_id', 'tf_value', 'cpm', 'cpc', 'cps'], 'integer'],
            [['tf_status', 'tf_type', 'sex'], 'string'],
            [['budget'], 'number'],
            [['create_at', 'update_at'], 'safe'],
            [['tag_ids', 'plan_number', 'age', 'degree'], 'string', 'max' => 20],
            [['plan_name'], 'string', 'max' => 30],
            [['tf_date', 'tf_period'], 'string', 'max' => 22],
            [['properties'], 'string', 'max' => 50],
            [['plan_number'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'old_plan_id' => 'Old Plan ID',
            'user_id' => '用户ID',
            'tag_ids' => 'Tag Ids',
            'plan_number' => '广告计划编号',
            'plan_name' => '广告计划名称',
            'tf_status' => '状态',
            'tf_type' => '投放类型值',
            'tf_value' => '投放值',
            'budget' => '预算',
            'tf_date' => '投放日期',
            'tf_period' => '投放时段',
            'properties' => '投放区域',
            'age' => '年龄',
            'sex' => '投放性别',
            'degree' => '学位',
            'cpm' => 'Cpm',
            'cpc' => 'Cpc',
            'cps' => 'Cps',
            'create_at' => '广告计划创建时间',
            'update_at' => '更新时间',
        ];
    }
}
