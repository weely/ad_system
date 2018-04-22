<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\AdPlans */
/* @var $form yii\widgets\ActiveForm */
?>

<link href="js/city-select-util/zyzn_1.css" type="text/css" rel="stylesheet">
<script type="text/javascript" src="js/city-select-util/jquery.min.js"></script>
<script type="text/javascript" src="js/city-select-util/City_data.js"></script>
<script type="text/javascript" src="js/city-select-util/areadata.js"></script>


<script>
    $(function(){
        //下一步
        $("#next-stage").click(function(){
            $("#page1").css('display','none');
            $("#page2").css('display','');
        });
        //上一步
        $("#previous-stage").click(function(){
            $("#page2").css('display','none');
            $("#page1").css('display','');
        });

        $("#cancel").click(function(){
            location.href = '/index.php?r=ad-plans';
        });

        $("input[name='radio_tf_type']").click(function(){
            switch (parseInt($(this).val())){
                case 1:
                    $("#lab-info").html("元/千次");
                    break;
                case 2:
                    $("#lab-info").html("元/次");
                    break;
                case 3:
                    $("#lab-info").html("元");
                    break;
                case 4:
                    $("#lab-info").html("元");
                    break;
                case 5:
                    $("#lab-info").html("元");
                    break;
            }
        });
        // $("#btn-cpa").click(function(){
        //     $("#lab-info").html("元");
        // });
        $("#save-to-sucai").click(function(){
            $("#is_redirect").val(1);
        });
        $("#save").click(function(){
            $("#is_redirect").val(0);
        });

    });

    function check() {
        if ($("#plan-name").val() == "" || $("#plan-name").val().length > 30) {
            alert("计划名称不符合要求");
            $("#page2").css('display','none');
            $("#page1").css('display','');
            $("#plan-name").focus();
            return false;
        }
        if ($("#cash").val() != '') {
            if (isNaN(parseInt($("#cash").val())) || parseInt($("#cash").val()) < 0) {
                alert("出价金额设置不恰当");
                $("#page2").css('display','none');
                $("#page1").css('display','');
                $("#budget").focus();
                return false;
            }
        }
        if ($("#budget").val() != '') {
            if (isNaN(parseFloat($("#budget").val())) || parseFloat($("#budget").val()) < 0) {
                alert("预算设置不恰当");
                $("#page2").css('display','none');
                $("#page1").css('display','');
                $("#budget").focus();
                return false;
            }
        }

    }

</script>


<div class="ad-plans-form">
    <form class="courses-form form-horizontal col-lg-9" action="/index.php?r=ad-plans/save" method="post" onsubmit="return check()">
        <input name="_csrf" type="hidden" id="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
        <input name="id" type="hidden" value="<?=$model->id?>">
        <!-- 第一页-->
        <div id="page1" style="margin-top: 100px;">
<!--            <div class="form-group">-->
<!--                <label class="form-label col-lg-2">广告计划编号:</label>-->
<!--                <div class="col-lg-8">-->
<!--                    <input type="text" id="plan-num" value="--><?php //echo $model->plan_number; ?><!--" name="plan_num" placeholder="请输入广告计划编号" class="form-control">-->
<!--                </div>-->
<!--<!--                <label style="color: gray;font-size: 12px;">字符不超过30个</label>-->
<!--            </div>-->
            <div class="form-group">
                <label class="form-label col-lg-2">广告计划名称:</label>
                <div class="col-lg-8">
                    <input type="text" id="plan-name" value="<?php echo $model->plan_name; ?>" name="plan_name" placeholder="请输入广告计划名称" class="form-control">
                </div>
                <label style="color: gray;font-size: 12px;">字符不超过30个</label>
            </div>
            <div class="form-group">
                <label class="form-label col-lg-2">投放方式:</label>
                <div class="col-lg-8">
                    <div class="form-group">
                        <label class="form-label col-lg-1">
                            <input type="radio" name="radio_tf_type" value="1" <?php echo $model->tf_type=='1' ?'checked':''; ?>>CPM</label>
                        <label class="form-label col-lg-1">
                            <input type="radio" name="radio_tf_type" value="2" <?php echo $model->tf_type=='2' ?'checked':''; ?>>CPC</label>
                        <label class="form-label col-lg-1">
                            <input type="radio" name="radio_tf_type" value="3" <?php echo $model->tf_type=='2' ?'checked':''; ?>>CPA</label>
                        <label class="form-label col-lg-1">
                            <input type="radio" name="radio_tf_type" value="4" <?php echo $model->tf_type=='4' ?'checked':''; ?>>CPL</label>
                        <label class="form-label col-lg-1">
                            <input type="radio" name="radio_tf_type" value="5" <?php echo $model->tf_type=='5' ?'checked':''; ?>>CPS</label>
                    </div>
<!--                    <div class="form-group btn-group" role="group" aria-label="">-->
<!--                        <input type="button" id="btn-cpa" class="btn btn-primary">CPA</input >-->
<!--                        <button type="button" id="btn-cpl" class="btn btn-default">CPL</button>-->
<!--                        <button type="button" id="btn-cps" class="btn btn-default">CPS</button>-->
<!--                        <button type="button" id="btn-cpm" class="btn btn-default" disabled>CPM</button>-->
<!--                        <button type="button" id="btn-cpc" class="btn btn-default" disabled>CPC</button>-->
<!--                    </div>-->
                    <div class="form-group">
                        <label class="form-label col-lg-3" style="color: gray;font-size: 12px;">出价金额:</label>
                        <div class="col-lg-6">
                            <input type="text" id="cash" value="<?php echo $model->tf_value; ?>" name="cash" placeholder="" class="form-control">
                        </div>
                        <label id="lab-info" style="color: gray;font-size: 12px;">元/千次</label>
                    </div>
                </div>
            </div>

            <div class="form-group" style="margin-top: 80px;">
                <label class="form-label col-lg-2">每日预算:</label>
                <div class="col-lg-8">
                    <input type="text" id="budget" value="<?php echo $model->budget; ?>" name="budget" class="form-control">
                </div>
                <label style="color: gray;font-size: 12px;">元</label>
            </div>

            <input type="button" id="next-stage" class="btn btn-primary" value="下一步" style="margin-top: 80px;">
        </div>
        <!-- 初始化隐藏的下一页-->
        <div id="page2" style="display: none">
            <div class="form-group">
                <label class="form-label col-lg-2">投放日期:</label>
                <div class="col-lg-8">
                    <div class="form-group">
                        <label class="form-label col-lg-2">
                            <input type="radio" name="radio_date" value="-1" <?php echo $model->tf_date=='不限'||$model->tf_date==null ? 'checked':''; ?>>不限</label>
                    </div>
                    <div class="form-group">
                        <label class="form-label col-lg-3">
                            <input type="radio" name="radio_date" value="1" <?php echo $model->tf_date=='不限'||$model->tf_date==null ? '':'checked'; ?>>自定义</label>
                        <div class="col-lg-4">
                            <input type="date" id="tf-date-begin" name="tf_date_begin" value="<?php
                                echo substr($model->tf_date,0,strpos($model->tf_date, ',')); ?>" class="form-control">
                        </div>
                        <label class="col-lg-1">到</label>
                        <div class="col-lg-4">
                            <input type="date" id="tf-date-end" name="tf_date_end" value="<?php
                                echo substr($model->tf_date, strpos($model->tf_date, ',')+1); ?>" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label col-lg-2">投放时间段:</label>
                <div class="col-lg-8">
                    <div class="form-group">
                        <label class="form-label col-lg-2">
                            <input type="radio" name="radio_time" value="-1" <?php echo $model->tf_period=='不限'||$model->tf_period==null ? 'checked':''; ?>>不限</label>
                    </div>
                    <div class="form-group">
                        <label class="form-label col-lg-3">
                            <input type="radio" name="radio_time" value="1" <?php echo $model->tf_period=='不限'||$model->tf_period==null ? '':'checked'; ?>>自定义</label>
                        <div class="col-lg-4">
                            <select id="opt-addr" name="tf_time_begin" class="form-control">
                            <?php foreach ($periods as $k => $v): ?>
                                <option value="<?php echo $k; ?>" <?php echo substr($model->tf_period,0,strpos($model->tf_period, ','))==$k ?'selected':''; ?>><?php echo $v ?></option>
                            <?php endforeach; ?>
                            </select>
                        </div>
                        <label class="col-lg-1">到</label>
                        <div class="col-lg-4">
<!--                            <input type="time" id="tf-time-end" name="tf_time_end" class="form-control">-->
                            <select id="opt-addr" name="tf_time_end" class="form-control">
                            <?php foreach ($periods as $k => $v): ?>
                                <option value="<?php echo $k; ?>" <?php echo substr($model->tf_period,strpos($model->tf_period, ',')+1)==$k ?'selected':''; ?>><?php echo $v ?></option>
                            <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label col-lg-2">投放地域:</label>
                <div class="col-lg-8">
                    <div class="form-group">
                        <label class="form-label col-lg-2">
                            <input type="radio" name="radio_addr" value="-1" <?php echo $model->properties=='不限'||$model->properties==null ? 'checked':''; ?>>不限</label>
                    </div>
                    <div class="form-group">
                        <label class="form-label col-lg-3">
                            <input type="radio" name="radio_addr" value="1" <?php echo $model->properties=='不限'||$model->properties==null ? '':'checked'; ?>>自定义</label>
                        <div class="col-lg-4">
                            <input type="text" name="opt_addr" class="form-control" value="<?php echo $model->properties; ?>" data-value="" placeholder="选择区域" onclick="appendCity(this,'duoxuan')" />
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label col-lg-2">定向标签:</label>
                <div class="col-lg-3">
                    <select id="opt-tags" name="opt_tags" class="form-control">
                        <?php foreach ($tags as $item): ?>
                            <option value="<?php echo $item['id']; ?>" <?php echo $model->tag_ids==$item['id'] ?'selected':''; ?>><?php echo $item['name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label col-lg-2">年龄:</label>
                <div class="col-lg-8">
                    <div class="form-group">
                        <label class="form-label col-lg-2">
                            <input type="radio" name="radio_age" value="-1" <?php echo $model->age=='不限'||$model->age==null ?'checked':''; ?>>不限</label>
                    </div>
                    <div class="form-group">
                        <label class="form-label col-lg-3"><input type="radio" name="radio_age" value="1" <?php echo $model->age=='不限'||$model->age==null ?'':'checked'; ?>>自定义</label>
                        <div class="col-lg-4">
                            <select id="opt-age-start" name="opt_age_start" class="form-control">
                                <?php foreach ($ages as $k=>$V): ?>
                                    <option value="<?php echo $k; ?>" <?php echo substr($model->age,0,strpos($model->age, ','))==$k ?'selected':''; ?>><?php echo $V; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <span class="col-lg-1">——</span>
                        <div class="col-lg-4">
                            <select id="opt-age-end" name="opt_age_end" class="form-control">
                                <?php foreach ($ages as $k=>$V): ?>
                                    <option value="<?php echo $k; ?>" <?php echo substr($model->age,strpos($model->age, ',')+1)==$k ?'selected':''; ?>><?php echo $V; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label col-lg-2">性别:</label>
                <div class="col-lg-8 form-group">
                    <label class="form-label col-lg-2"><input type="radio" name="radio_gender" value="0" <?php echo $model->sex=='0'||$model->sex==null ?'checked':''; ?>>不限</label>
                    <label class="form-label col-lg-2"><input type="radio" name="radio_gender" value="1" <?php echo $model->sex=='1' ?'checked':''; ?>>男</label>
                    <label class="form-label col-lg-2"><input type="radio" name="radio_gender" value="-1" <?php echo $model->sex=='-1' ?'checked':''; ?>>女</label>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label col-lg-2">学历:</label>
                <div class="col-lg-8">
                    <div class="form-group">
                        <label class="form-label col-lg-2">
                            <input type="radio" name="radio_degree" value="-1" <?php echo $model->degree=='不限'||$model->degree==null ?'checked':''; ?>>不限</label>
                    </div>
                    <div class="form-group">
                        <label class="form-label col-lg-3">
                            <input type="radio" name="radio_degree" value="1" <?php echo $model->degree=='不限'||$model->degree==null ?'':'checked'; ?>>自定义</label>
                        <div class="col-lg-4">
                            <select id="opt-degree-start" name="opt_degree_start" class="form-control">
                                <?php foreach ($degrees as $k=>$V): ?>
                                    <option value="<?php echo $k; ?>" <?php echo substr($model->degree,0,strpos($model->degree, ','))==$k ?'selected':''; ?>><?php echo $V; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <span class="col-lg-1">——</span>
                        <div class="col-lg-4">
                            <select id="opt-degree-end" name="opt_degree_end" class="form-control">
                                <?php foreach ($degrees as $k=>$V): ?>
                                    <option value="<?php echo $k; ?>" <?php echo substr($model->degree,strpos($model->degree, ',')+1)==$k ?'selected':''; ?>><?php echo $V; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <input type="button" id="previous-stage" class="btn btn-default" value="上一步" style="margin-right: 30px;">
            <input name="is_redirect" type="hidden" id="is_redirect" value="">
            <input type="submit" id="save-to-sucai" class="btn btn-primary" value="保存并新建素材" style="margin-right: 30px;">
            <input type="submit" id="save" class="btn btn-default" value="保存设置" style="margin-right: 30px;">
            <input type="button" id="cancel" class="btn btn-default" value="取消">
        </div>
    </form>
</div>
