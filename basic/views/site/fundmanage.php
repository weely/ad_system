<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

//$this->title = 'About';
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container">
<!--    <code>--><?//= __FILE__ ?><!--</code>-->
<!--    <div class="row">-->
        <div class="form-group col-lg-12" style="margin-top: 10px;">
            <div class="col-lg-12">
            <fieldset>
                <legend>账户信息:</legend>
                <div class="form-group col-lg-12" style="padding: 25px;">
                    <div class="col-lg-offset-1 col-lg-5">
                        <label>账户可用金额(元)：</label>
                        <label style="color: #b7b522;"><?= $data['avail_fund'] ?></label>
                    </div>
                    <div class="col-lg-offset-1 col-lg-5">
                        <label>账户充值请联系我们：</label>
                        <label><?= $data['mobile'] ?></label>
                    </div>
                </div>
            </fieldset>
            </div>
        </div>

        <div class="form-group col-lg-12" style="margin-top: 10px;">
            <div class="col-lg-6">
            <fieldset>
                <table class="table table-striped table-bordered">
                <caption>
                    <label for="adPlan" class="col-lg-3 control-label">支出明细</label>
                    <div class="col-lg-3">
                        <select class="form-control">
                            <option>1</option>
                            <option>2</option>
                        </select>
                    </div>
                    <label for="adPlan" class="col-lg-4 control-label">累积消费(元)：</label>
                </caption>
                <thead>
                    <tr><th>日期</th><th>支出金额(元)</th></tr>
                </thead>
                <tbody>
                <?php
                    foreach ($data['flow_outs'] as $flow_out) {
                        echo "<tr><td>" . $flow_out['create_at'] . "</td><td>".$flow_out['capital']."</td></tr>";
                    }
                ?>
                </tbody>
            </table>
            </fieldset>
            </div>

            <div class="col-lg-6">
                <fieldset>
            <table class="table table-striped table-bordered">
                <caption>
                    <label for="adPlan" class="col-lg-3 control-label">充值记录</label>
                    <div class="col-lg-3">
                        <select class="form-control">
                            <option>1</option>
                            <option>2</option>
                        </select>
                    </div>
                    <label for="adPlan" class="col-lg-4 control-label">累积充值(元)：</label>
                </caption>
                <thead>
                <tr><th>日期</th><th>充值金额(元)</th></tr>
                </thead>
                <tbody>
                <?php
                    foreach ($data['flow_Ins'] as $flow_In) {
                        echo "<tr><td>" . $flow_In['create_at'] . "</td><td>".$flow_In['capital']."</td></tr>";
                    }
                ?>
                </tbody>
            </table>
                </fieldset>
            </div>
        </div>
<!--    </div>-->
</div>
