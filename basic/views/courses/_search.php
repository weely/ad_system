<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\CoursesSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="courses-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'id_project') ?>

    <?= $form->field($model, 'user_id') ?>

    <?= $form->field($model, 'plan_id') ?>

    <?= $form->field($model, 'tf_status') ?>

    <?php // echo $form->field($model, 'tf_type') ?>

    <?php // echo $form->field($model, 'tf_value') ?>

    <?php // echo $form->field($model, 'is_online') ?>

    <?php // echo $form->field($model, 'is_h5') ?>

    <?php // echo $form->field($model, 'ad_sc_title') ?>

    <?php // echo $form->field($model, 'ad_type') ?>

    <?php // echo $form->field($model, 'tag_ids') ?>

    <?php // echo $form->field($model, 'logo') ?>

    <?php // echo $form->field($model, 'img_html') ?>

    <?php // echo $form->field($model, 'tags') ?>

    <?php // echo $form->field($model, 'create_at') ?>

    <?php // echo $form->field($model, 'update_at') ?>

    <?php // echo $form->field($model, 'cpm') ?>

    <?php // echo $form->field($model, 'cpc') ?>

    <?php // echo $form->field($model, 'cpa') ?>

    <?php // echo $form->field($model, 'cpl') ?>

    <?php // echo $form->field($model, 'cps') ?>

    <?php // echo $form->field($model, 'today') ?>

    <?php // echo $form->field($model, 'total') ?>

    <?php // echo $form->field($model, 'zhaopin_card') ?>

    <?php // echo $form->field($model, 'highpin_card') ?>

    <?php // echo $form->field($model, 'zhaopin_html') ?>

    <?php // echo $form->field($model, 'message_text') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
