<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\AdPlans */

/**
    //$this->title = "计划详情";
    //$this->title = $model->id;
    //$this->params['breadcrumbs'][] = ['label' => 'Ad Plans', 'url' => ['index']];
    //$this->params['breadcrumbs'][] = $this->title;


    //    <h2><?= Html::encode($this->title) ?><!--</h2>-->
    <!--<p>-->
    <!--    --><?//= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
    <!--    --><?//= Html::a('Delete', ['delete', 'id' => $model->id], [
    //        'class' => 'btn btn-danger',
    //        'data' => [
    //            'confirm' => 'Are you sure you want to delete this item?',
    //            'method' => 'post',
    //        ],
    //    ]) ?>
    <!--</p>-->
*/
$this->context->layout = false;
?>
<div class="ad-plans-view">

    <div>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
//            'id',
//            'old_plan_id',
//            'user_id',
//            'tag_ids',
            'plan_number',
            'plan_name',
            'tf_status',
            'tf_type',
            'tf_value',
            'budget',
            'tf_date',
            'tf_period',
            'properties',
            'age',
            'sex',
            'degree',
            'create_at',
            'update_at',
        ],
    ]) ?>
    </div>
</div>
