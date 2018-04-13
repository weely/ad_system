<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "his_user_data".
 *
 * @property string $id
 * @property string $data_id
 * @property string $user_id
 * @property string $course_id
 * @property string $info_name
 * @property int $info_gender 1,男 2,女
 * @property string $info_mobile
 * @property string $info_company
 * @property string $info_position
 * @property int $flag 是否是韦博英语未提交的数据（0，是，1不是）
 * @property string $state 1:预约；2:点击；
 * @property string $create_at
 * @property string $date_at
 */
class HisUserData extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'his_user_data';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['data_id', 'date_at'], 'required'],
            [['data_id', 'user_id', 'course_id'], 'integer'],
            [['state'], 'string'],
            [['create_at', 'date_at'], 'safe'],
            [['info_name', 'info_mobile', 'info_company', 'info_position'], 'string', 'max' => 50],
            [['info_gender'], 'string', 'max' => 3],
            [['flag'], 'string', 'max' => 1],
            [['course_id', 'info_mobile'], 'unique', 'targetAttribute' => ['course_id', 'info_mobile']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'data_id' => 'Data ID',
            'user_id' => 'User ID',
            'course_id' => 'Course ID',
            'info_name' => 'Info Name',
            'info_gender' => 'Info Gender',
            'info_mobile' => 'Info Mobile',
            'info_company' => 'Info Company',
            'info_position' => 'Info Position',
            'flag' => 'Flag',
            'state' => 'State',
            'create_at' => 'Create At',
            'date_at' => 'Date At',
        ];
    }
}
