<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\AdPlans */

$this->title = '新建广告计划';
//$this->params['breadcrumbs'][] = ['label' => 'Ad Plans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

/**
 * <h3><?= Html::encode($this->title) ?></h3>
 */
?>
<div class="ad-plans-create">

    <?= $this->render('_form', [
        'model' => $model,
        'periods' => $periods,
        'tags' => $tags,
        'ages' => $ages,
        'degrees' => $degrees,
    ]) ?>
</div>
