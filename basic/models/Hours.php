<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "hours".
 *
 * @property string $id
 * @property string $time
 * @property string $cid
 * @property string $show
 * @property string $view
 * @property string $book
 */
class Hours extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'hours';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['time'], 'safe'],
            [['cid', 'show', 'view', 'book'], 'integer'],
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
            'show' => 'Show',
            'view' => 'View',
            'book' => 'Book',
        ];
    }
}
