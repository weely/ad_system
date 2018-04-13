<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\AdPlansSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ad-plans-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'old_plan_id') ?>

    <?= $form->field($model, 'user_id') ?>

    <?= $form->field($model, 'tag_ids') ?>

    <?= $form->field($model, 'plan_number') ?>

    <?php // echo $form->field($model, 'plan_name') ?>

    <?php // echo $form->field($model, 'tf_status') ?>

    <?php // echo $form->field($model, 'tf_type') ?>

    <?php // echo $form->field($model, 'tf_value') ?>

    <?php // echo $form->field($model, 'budget') ?>

    <?php // echo $form->field($model, 'tf_period') ?>

    <?php // echo $form->field($model, 'properties') ?>

    <?php // echo $form->field($model, 'year') ?>

    <?php // echo $form->field($model, 'sex') ?>

    <?php // echo $form->field($model, 'degree') ?>

    <?php // echo $form->field($model, 'cpm') ?>

    <?php // echo $form->field($model, 'cpc') ?>

    <?php // echo $form->field($model, 'cps') ?>

    <?php // echo $form->field($model, 'create_at') ?>

    <?php // echo $form->field($model, 'update_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
