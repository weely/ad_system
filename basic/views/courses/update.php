<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Courses */

$this->title = '设置广告素材';
//$this->params['breadcrumbs'][] = ['label' => 'Courses', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '设置广告素材';
?>
<div class="courses-update">

    <h2><?= Html::encode($this->title) ?></h2>

    <?= $this->render('_form', [
        'model' => $model,
        'plans' => $plans,
    ]) ?>

</div>
