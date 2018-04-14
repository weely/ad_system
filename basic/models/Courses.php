<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "courses".
 *
 * @property string $id
 * @property string $id_project
 * @property string $user_id 广告主id
 * @property string $plan_id 计划id
 * @property int $tf_status 投放状态：1、投放中，2、待投放。0、待审核
 * @property string $tf_type 投放模式：1：CPM, 2: CPC, 3: CPA,4:CPL,5:CPS
 * @property int $tf_value 投放值
 * @property string $is_online 是否上线
 * @property string $title_img 图片标题路径
 * @property string $is_h5 0:文字模式，1: 图片模式，2：双模式
 * @property string $ad_sc_title 广告素材标题
 * @property string $ad_type 广告类型
 * @property string $tag_ids 广告类型标签
 * @property string $logo logo
 * @property string $img_html 详情页大图
 * @property string $properties 广告特点
 * @property string $tags 广告标签
 * @property string $create_at 创建时间
 * @property string $update_at 更新时间
 * @property string $cpm
 * @property string $cpc
 * @property int $cpa
 * @property int $cpl
 * @property string $cps
 * @property string $today
 * @property string $total 总的预约值
 * @property string $zhaopin_card 智联card版本
 * @property string $highpin_card 卓聘card版本
 * @property string $zhaopin_html html版本
 * @property string $message_text
 */
class Courses extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'courses';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_project', 'user_id', 'plan_id', 'tf_status', 'tf_value', 'cpm', 'cpc', 'cpa', 'cpl', 'cps', 'today', 'total'], 'integer'],
            [['plan_id'], 'required'],
            [['tf_type', 'is_online', 'is_h5'], 'string'],
            [['create_at', 'update_at'], 'safe'],
            [['title_img', 'ad_sc_title', 'properties', 'tags'], 'string', 'max' => 50],
            [['ad_type'], 'string', 'max' => 20],
            [['tag_ids'], 'string', 'max' => 30],
            [['logo', 'img_html', 'zhaopin_card', 'highpin_card', 'zhaopin_html', 'message_text'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '广告素材ID',
            'id_project' => 'Id_Project',
            'user_id' => '用户ID',
            'plan_id' => '所属计划ID',
            'tf_status' => '素材投放状态',
            'tf_type' => '投放类型',
            'tf_value' => '投放值',
            'is_online' => '上线状态',
            'is_h5' => '素材是否为H5',
            'title_img' => '标题图片路径',
            'ad_sc_title' => '素材标题',
            'ad_type' => '素材类型',
            'tag_ids' => '素材所属类型ID',
            'logo' => 'Logo',
            'img_html' => 'H5大图',
            'properties' => '广告特点',
            'tags' => 'Tags',
            'cpm' => 'Cpm量',
            'cpc' => 'Cpc量',
            'cpa' => 'Cpa量',
            'cpl' => 'Cpl量',
            'cps' => 'Cps量',
            'today' => 'Today',
            'total' => 'Total',
            'zhaopin_card' => 'Zhaopin Card',
            'highpin_card' => 'Highpin Card',
            'zhaopin_html' => 'Zhaopin Html',
            'message_text' => 'Message Text',
            'create_at' => '素材创建时间',
            'update_at' => '素材更新时间',
        ];
    }
}
