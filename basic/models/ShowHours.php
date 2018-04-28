<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "show_hours".
 *
 * @property string $id
 * @property string $time
 * @property string $cid
 * @property string $show_num
 * @property string $click_num
 * @property string $book_num
 */
class ShowHours extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'show_hours';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['time'], 'safe'],
            [['cid', 'show_num', 'click_num', 'book_num'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'time' => 'Time',
            'cid' => 'Cid',
            'show_num' => 'Show Num',
            'click_num' => 'Click Num',
            'book_num' => 'Book Num',
        ];
    }
}
