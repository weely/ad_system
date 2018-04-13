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
    </div>
</div>