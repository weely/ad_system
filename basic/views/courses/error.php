<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Courses */

$this->title = "提示";

//$this->context->layout = false;
?>

<div class="container">
    <div class="alert alert-danger">
        <?= nl2br(Html::encode($message)) ?>
    </div>

    <?php
        header('Refresh:3,Url=/index.php?r=ad-plans/create');
        echo '<label id="time-hint">3</label>s后自动跳转到新建广告计划页面';
    ?>
</div>

<script>
    $(function(){
        setInterval(setTimeHint,1000);
    });
    function setTimeHint(){
        var num = parseInt($("#time-hint").html());
        if (num>0){
            num -= 1;
        }
        $("#time-hint").html(num);
    }
</script>