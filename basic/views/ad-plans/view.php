<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\AdPlans */

$this->context->layout = false;
?>
<div class="ad-plans-view">

    <div>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
//            'id',
//            'user_id',
//            'tag_ids',
//            'plan_number',
            'plan_name',
            [
            'attribute'=>'tf_status',
            'value'=>$model->tf_status == '1' ? '开启' : '关闭',
            ],
//            'tf_status',
            [
                'attribute'=>'tf_type',
                'value'=> $model->tf_type == '1'?'CPM':($model->tf_type=='2'?'CPC':
                    ($model->tf_type=='3'?'CPA':($model->tf_type=='4'?'CPL':($model->tf_type=='5'?'CPS':'')))),
            ],
            'tf_value',
            'budget',
            [
                'attribute'=>'tf_date',
                'value'=> $model->tf_date ? (stripos($model->tf_date,',') !==false ?
                    preg_replace('/,/i','~',$model->tf_date) : $model->tf_date ) : '不限'
            ],
            [
                'attribute'=>'tf_period',
                'value'=> $model->tf_period ? (stripos($model->tf_period,',') !==false ?
                    preg_replace('/,/i','~',$model->tf_period) : $model->tf_period ) : '不限'
            ],
            [
                'attribute'=>'properties',
                'value'=>$model->properties == '' ? '不限' : $model->properties,
            ],
            [
                'attribute'=>'age',
                'value'=>$model->age == '' ? '不限' : $model->age,
            ],
            [
                'attribute'=>'sex',
                'value'=>$model->sex == '0' ? '不限' : ($model->sex == '1' ? '男' : '女'),
            ],
            [
                'attribute'=>'degree',
                'value'=> stripos($model->degree,',') !==false ?
                    (Yii::$app->params['tf_degree'][explode(',', $model->degree)[0]] . '~' .
                    Yii::$app->params['tf_degree'][explode(',', $model->degree)[1]]) :
                    Yii::$app->params['tf_degree'][$model->degree],
            ],
            'create_at',
            'update_at',
        ],
    ]) ?>
    </div>
</div>
