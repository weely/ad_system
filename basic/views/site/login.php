<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use yii\bootstrap\ActiveForm;

AppAsset::register($this);
//$this->title = 'Login';
//$this->params['breadcrumbs'][] = $this->title;
$this->context->layout = false;
?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
        <style>
            fieldset{padding:.35em .625em .75em;margin:0 2px;border:1px solid silver}
            legend{padding:.5em;border:0;width:auto}
        </style>
    </head>
    <body>
    <?php $this->beginBody() ?>
        <div class="container" style="text-align:center;padding-top: 100px;">
            <h1></h1>
            <h1 style="margin: 30px;">登录</h1>
            <?php $form = ActiveForm::begin([
                'id' => 'login-form',
                'options' => ['class'=>'form-horizontal'],
                'fieldConfig' => [
                    'template' => "{label}\n<div class=\"col-lg-4\">{input}</div>\n<div class=\"col-lg-3\">{error}</div>",
                    'labelOptions' => ['class' => 'col-lg-offset-3 col-lg-1 control-label'],
                ],
            ]); ?>
                <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

                <?= $form->field($model, 'password')->passwordInput() ?>

                <?= $form->field($model, 'rememberMe')->checkbox([
                    'template' => "<div>{input} {label}</div>\n<div class=\"col-lg-offset-3\">{error}</div>",
                ]) ?>

                <div class="form-group">
                    <div>
                        <?= Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                    </div>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage() ?>