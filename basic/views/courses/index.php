<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CoursesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Courses';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="courses-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Courses', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'id_project',
            'user_id',
            'plan_id',
            'tf_status',
            //'tf_type',
            //'tf_value',
            //'is_online',
            //'is_h5',
            //'ad_sc_title',
            //'ad_type',
            //'tag_ids',
            //'logo',
            //'img_html',
            //'tags',
            //'create_at',
            //'update_at',
            //'cpm',
            //'cpc',
            //'cpa',
            //'cpl',
            //'cps',
            //'today',
            //'total',
            //'zhaopin_card',
            //'highpin_card',
            //'zhaopin_html',
            //'message_text',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
