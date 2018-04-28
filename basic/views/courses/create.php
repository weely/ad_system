<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Courses */

//$this->context->layout = false;

$this->title = '设置广告素材';
//$this->params['breadcrumbs'][] = ['label' => 'Courses', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="courses-create">

    <?= $this->render('_form', [
        'model' => $model,
        'plans' => $plans,
        'course_labels' => $course_labels
    ]) ?>


</div>
