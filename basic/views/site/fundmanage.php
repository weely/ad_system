<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\widgets\LinkPager;

//$this->title = 'About';
//$this->params['breadcrumbs'][] = $this->title;
?>
<script>
    $(function(){
        $("#begin_out").change(function(){
            var begin_out = $(this).val()
            var end_out = $("#end_out").val()
            if (begin_out != '' && end_out != '') {
                if (begin_out<=end_out) {
                    var req_url = location.href;
                    if (req_url.match(/&begin_out=(\d{4}-\d{1,2}-\d{1,2})+&end_out=(\d{4}-\d{1,2}-\d{1,2})+/)) {
                        req_url = req_url.replace(/&begin_out=(\d{4}-\d{1,2}-\d{1,2})+&end_out=(\d{4}-\d{1,2}-\d{1,2})+/,
                            '&begin_out=' + begin_out + '&end_out=' + end_out);
                    } else {
                        req_url += '&begin_out=' + begin_out + '&end_out=' + end_out;
                    }
                    location.href = req_url
                } else {
                    alert("提示：日期选择不恰当！");
                }
            }
        });
        $("#end_out").change(function(){
            var begin_out = $("#begin_out").val()
            var end_out = $(this).val()
            if (begin_out != '' && end_out != '') {
                if (begin_out<=end_out) {
                    var req_url = location.href;
                    if (req_url.match(/&begin_out=(\d{4}-\d{1,2}-\d{1,2})+&end_out=(\d{4}-\d{1,2}-\d{1,2})+/)) {
                        req_url = req_url.replace(/&begin_out=(\d{4}-\d{1,2}-\d{1,2})+&end_out=(\d{4}-\d{1,2}-\d{1,2})+/,
                            '&begin_out=' + begin_out + '&end_out=' + end_out);
                    } else {
                        req_url += '&begin_out=' + begin_out + '&end_out=' + end_out;
                    }
                    location.href = req_url
                } else {
                    alert("提示：日期选择不恰当！");
                }
            }
        });
        $("#begin_in").change(function(){
            var begin_in = $(this).val()
            var end_in = $("#end_in").val()
            if (begin_in != '' && end_in != '') {
                if (begin_in<=end_in) {
                    var req_url = location.href;
                    if (req_url.match(/&begin_in=(\d{4}-\d{1,2}-\d{1,2})+&end_in=(\d{4}-\d{1,2}-\d{1,2})+/)) {
                        req_url = req_url.replace(/&begin_in=(\d{4}-\d{1,2}-\d{1,2})+&end_in=(\d{4}-\d{1,2}-\d{1,2})+/,
                            '&begin_in=' + begin_in + '&end_in=' + end_in);
                    } else {
                        req_url += '&begin_in=' + begin_in + '&end_in=' + end_in;
                    }
                    location.href = req_url
                } else {
                    alert("提示：日期选择不恰当！");
                }
            }
        });
        $("#end_in").change(function(){
            var begin_in = $("#begin_in").val()
            var end_in = $(this).val()
            if (begin_in != '' && end_in != '') {
                if (begin_in<=end_in) {
                    var req_url = location.href;
                    if (req_url.match(/&begin_in=(\d{4}-\d{1,2}-\d{1,2})+&end_in=(\d{4}-\d{1,2}-\d{1,2})+/)) {
                        req_url = req_url.replace(/&begin_in=(\d{4}-\d{1,2}-\d{1,2})+&end_in=(\d{4}-\d{1,2}-\d{1,2})+/,
                            '&begin_in=' + begin_in + '&end_in=' + end_in);
                    } else {
                        req_url += '&begin_in=' + begin_in + '&end_in=' + end_in;
                    }
                    location.href = req_url
                } else {
                    alert("提示：日期选择不恰当！");
                }
            }
        });

    });
</script>

<div class="container">
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
                    <label for="adPlan" class="col-lg-2 control-label" style="font-size: 10px;">支出明细</label>
                    <div class="col-lg-3" style="padding: 0">
                        <input type="date" id="begin_out" class="form-control" style="font-size: 10px;" name="begin_out" value="<?=$begin_out?>"></div>
                    <div class="col-lg-3" style="padding: 0">
                        <input type="date" class="form-control" id="end_out" style="font-size: 10px;" name="end_out" value="<?=$end_out?>">
                    </div>
                    <label for="adPlan" class="col-lg-3 control-label" style="font-size: 10px;">累积消费(元)：<?=Html::encode($total_outs)?></label>
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
                <?= LinkPager::widget(['pagination' => $out_page,
                    'nextPageLabel' => '下一页',
                    'prevPageLabel' => '上一页',
                ]) ?>
            </div>

            <div class="col-lg-6">
                <fieldset>
            <table class="table table-striped table-bordered">
                <caption>
                    <label for="adPlan" class="col-lg-2 control-label" style="font-size: 10px;">充值记录</label>
                    <div class="col-lg-3" style="padding: 0px;">
                        <input type="date" class="form-control" style="font-size: 10px;" id="begin_in" name="begin_in" value="<?=$begin_in?>"></div>
                    <div class="col-lg-3" style="padding: 0px;">
                        <input type="date" class="form-control" style="font-size: 10px;" id="end_in" name="end_in" value="<?=$end_in?>">
                    </div>
                    <label for="adPlan" class="col-lg-3 control-label" style="font-size: 10px;">累积充值(元)：<?=Html::encode($total_ins)?></label>
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
                <?= LinkPager::widget(['pagination' => $in_page,
                    'nextPageLabel' => '下一页',
                    'prevPageLabel' => '上一页',
                ]) ?>
            </div>
        </div>
<!--    </div>-->
</div>
