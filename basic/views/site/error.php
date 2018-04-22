<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$this->title = "错误提示页：";
?>
<div class="site-error">

    <h2><?= Html::encode($this->title) ?></h2>
    <br>
    <div class="alert alert-danger">
        <?= nl2br(Html::encode($message)) ?>
    </div>

<!--    <p>-->
<!--        提示：服务器处理请求过程中出现了以下错误:-->
<!--    </p>-->
    <p>
        出现未知错误，请联系管理员.
    </p>

</div>
