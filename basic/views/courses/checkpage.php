<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = "提交审核";

?>
<div class="container">

    <h3><?= Html::encode($this->title) ?></h3>
    <div class="form-group" style="text-align: center;padding-top: 100px;">

        <span class="glyphicon glyphicon-ok-circle" style="margin: 20px;color: #8a8f2f;font-size:50px;"></span>

        <p>提交成功，工作人员会在48小时内完成审核，请耐心等待。</p>
        <p>
            <?php
            header('Refresh:3,Url=/index.php?r=site/ad-manage#sucai');
            echo '<label id="time-hint">3</label>s后自动跳转到新建广告素材页面';
            ?>
        </p>
    </div>
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