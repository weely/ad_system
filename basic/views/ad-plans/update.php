<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\AdPlans */

$this->title = '更新广告计划';
//$this->params['breadcrumbs'][] = ['label' => 'Ad Plans', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '更新计划';
?>
<div class="ad-plans-update">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
        'periods' => $periods,
        'tags' => $tags,
        'ages' => $ages,
        'degrees' => $degrees,
    ]) ?>

</div>
