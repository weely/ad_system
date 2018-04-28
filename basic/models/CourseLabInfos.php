<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "course_lab_infos".
 *
 * @property string $id
 * @property string $course_id
 * @property string $lab_1
 * @property string $lab_2
 * @property string $lab_3
 * @property string $lab_4
 * @property string $lab_5
 * @property string $lab_6
 * @property string $lab_7
 * @property string $lab_8
 */
class CourseLabInfos extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'course_lab_infos';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['course_id'], 'required'],
            [['course_id'], 'integer'],
            [['lab_1'], 'string', 'max' => 20],
            [['lab_2', 'lab_4', 'lab_5', 'lab_6', 'lab_8'], 'string', 'max' => 10],
            [['lab_3'], 'string', 'max' => 5],
            [['lab_7'], 'string', 'max' => 30],
            [['course_id'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'course_id' => 'Course ID',
            'lab_1' => 'Lab 1',
            'lab_2' => 'Lab 2',
            'lab_3' => 'Lab 3',
            'lab_4' => 'Lab 4',
            'lab_5' => 'Lab 5',
            'lab_6' => 'Lab 6',
            'lab_7' => 'Lab 7',
            'lab_8' => 'Lab 8',
        ];
    }
}
