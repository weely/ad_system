<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\LinkPager;

/* @var $this yii\web\View */
/* @var $searchModel app\models\AdPlansSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
//$this->context->layout = false;
?>
<link rel="stylesheet" href="lib/switch/switch.css">

<div class="container">
    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist" id="myTabs">
        <li id="plan_li" role="presentation">
            <a href="#plan-panel" aria-controls="plan" role="tab" data-toggle="tab">广告计划</a></li>
        <li id='sucai_li' role="presentation">
            <a href="#sucai-panel" aria-controls="sucai" role="tab" data-toggle="tab">广告素材</a></li>
    </ul>
    <!-- Tab panes -->
    <div class="tab-content">
        <!-- Tab plans -->
        <div role="tabpanel" class="tab-pane" id="plan-panel">
            <br>
            <div class="form-group col-lg-12">
                <div class="col-lg-2">
                <select class="form-control" id="tfStatus" onchange="selectByParams(this, 'status')">
                    <?php foreach ($data['plan_status_select'] as $item): ?>
                        <option value="<?php echo $item['code'];?>" <?php if ($item['code'] === $data['tf_status']) {echo 'selected="selected"';} ?> >
                            <?php echo $item['value']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                </div>
                <div class="col-lg-2">
                    <select class="form-control" id="tfType" onchange="selectByParams(this, 'tf_type')">
                        <?php foreach ($data['tf_type_select'] as $item): ?>
                            <option value="<?php echo $item['code'];?>" <?php if ($item['code'] === $data['tf_type']) {echo 'selected="selected"';} ?> >
                                <?php echo $item['value']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
<!--                <div class="col-lg-3" style="display: inline-flex">-->
<!--                    <label>广告投放时间:</label>-->
<!--                    <input type="date" class="form-control">-->
<!--                </div>-->
            </div>
            <div class="form-group col-lg-12">
                <table class="table table-striped table-bordered">
                    <caption>
                        <div class="col-lg-3">
                            <a class="btn btn-info" href="/index.php?r=ad-plans/create"><i class="glyphicon glyphicon-plus"></i>新建计划</a>
                        </div>
                        <div class="col-lg-offset-11">
                            <label>合计-</label>
                            <label><?php echo $data['sum_plans'] ?> </label>
<!--                            <button class="btn btn-default">导出数据</button>-->
                        </div>
                    </caption>
                    <thead>
                    <tr>
                        <?php
                            foreach($data['planTitles'] as $title){
                                echo "<th>" . $title  ."</th>";
                            }
                        ?>
                    </tr>
                    </thead>
                    <tbody id="ad-plans">
                    <?php foreach ($data['ad_plans'] as $plan) : ?>
                        <tr>
                            <td>
                                <input class="mui-switch mui-switch-animbg" id="<?php echo "plan_".$plan['id']?>" type="checkbox" <?php echo $plan['tf_status']==1 ? 'checked' : '';?> onclick="onClickPlanCheckBox(this)">
                            </td>
                            <td><a href="/index.php?r=ad-plans/view&id=<?php echo $plan['id']; ?>" data-remote="false" data-toggle="modal" data-target="#data-modal" data-whatever="计划">
                                <?php echo $plan['plan_name']; ?>
                                </a></td>
                            <td><?php echo Yii::$app->params['ad_status'][$plan['tf_status']]; ?></td>
                            <td style="font-size: 12px;"><?php echo $plan['tf_date']; ?></td>
                            <td><?php echo $plan['tf_period']; ?></td>
                            <td><?php echo Yii::$app->params['tf_type'][$plan['tf_type']]; ?></td>
                            <td><?php echo $plan['budget']; ?></td>
                            <!-- TODO 计划详情等链接-->
                            <td><a href="/index.php?r=ad-plans/courses-by-plan&plan_id=<?php echo $plan['id']; ?>" data-remote="false" data-toggle="modal" data-target="#data-modal" data-whatever="查看素材">查看素材</a>
                            <a href="/index.php?r=ad-plans/update&id=<?php echo $plan['id'];?>"><span class="glyphicon glyphicon-pencil"></span></a>
                            <a href="/index.php?r=ad-plans/delete&id=<?php echo $plan['id'];?>" data-confirm="您确认该计划删除吗？" data-method="post">
                                <span class="glyphicon glyphicon-trash"></a></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?= LinkPager::widget(['pagination' => $ad_page,
                'nextPageLabel' => '下一页',
                'prevPageLabel' => '上一页',
                ]) ?>
        </div>
        <!-- Tab sucai -->
        <div role="tabpanel" class="tab-pane" id="sucai-panel">
            <br>
            <div class="form-group col-lg-12">
                <div class="col-lg-2">
                    <select class="form-control" id="ctfStatus" onchange="selectByParams(this, 'sc_status')">
                        <?php foreach ($data['tf_status_select'] as $item): ?>
                            <option value="<?php echo $item['code'];?>" <?php if ($item['code'] === $data['sc_status']) {echo 'selected="selected"';} ?> >
                                <?php echo $item['value']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-lg-2">
                    <select class="form-control" id="ctfType" onchange="selectCourseByPlan(this)">
                        <?php foreach ($data['total_plans'] as $item): ?>
                            <option value="<?php echo $item['id'];?>" <?php if ($item['id'] === $data['sel_plan']) {echo 'selected="selected"';} ?> >
                                <?php echo $item['plan_name']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-lg-3" style="display: inline-flex">
                    <label>广告投放时间:</label>
                    <input type="date" class="form-control">
                </div>
            </div>
            <table class="table table-striped table-bordered">
<!--                <caption style="background-color: #d2d2bb;">-->
                <caption>
                    <div class="col-lg-3">
                        <a class="btn btn-info" href="/index.php?r=courses/create"><i class="glyphicon glyphicon-plus"></i>新建素材</a>
                    </div>
                    <div class="col-lg-offset-11">
                        <label>合计-</label>
                        <label><?php echo $data['sum_courses'] ?> </label>
                        <!--                            <button class="btn btn-default">导出数据</button>-->
                    </div>
                </caption>
                <thead>
                <tr>
                    <?php
                    foreach($data['courseTitles'] as $title){
                        echo "<th>" . $title  ."</th>";
                    }
                    ?>
                </tr>
                </thead>
                <tbody id="courses">
                <?php foreach ($data['ad_courses'] as $course): ?>
                    <tr>
<!--                        <td>--><?php //echo $course['tf_status']; ?><!--</td>-->
                        <td>
                            <input class="mui-switch mui-switch-animbg" id="<?php echo "course_".$course['id']?>" type="checkbox" <?php echo $course['tf_status']==1 ? 'checked' : '';?> onclick="onClickCourseCheckBox(this)">
                        </td>
                        <td><a href="/index.php?r=courses/view&id=<?php echo $course['id']; ?>" data-remote="false" data-toggle="modal" data-target="#data-modal" data-whatever="素材">
                                <?php echo $course['ad_sc_title']; ?>
                            </a></td>
<!--                    <td><a href="/index.php?r=courses/view&id=--><?php //echo $course['id']; ?><!--">--><?php //echo $course['ad_sc_title']; ?><!--</a></td>-->
                    <td><?php echo Yii::$app->params['tf_status'][$course['tf_status']]; ?></td>
                    <td><?php echo $course['plan_name']; ?></td>
<!--                    // TODO 计划详情等链接-->
                    <td><a href="/index.php?r=courses/view&id=<?php echo $course['id']; ?>" data-remote="false" data-toggle="modal" data-target="#data-modal" data-whatever="素材">
                            <span class="glyphicon glyphicon-eye-open"></span>
                        </a>
                        <a href="/index.php?r=courses/update&id=<?php echo $course['id']; ?>">
                            <span class="glyphicon glyphicon-pencil"></span>
                        </a>
                        <a href="/index.php?r=courses/delete&id=<?php echo $course['id']; ?>" data-confirm="您确认该计划删除吗？" data-method="post">
                            <span class="glyphicon glyphicon-trash"></a></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <?= LinkPager::widget(['pagination' => $sc_page,
                'nextPageLabel' => '下一页',
                'prevPageLabel' => '上一页',
                ]) ?>
        </div>
    </div>
    <!--modal-->
    <div class="modal fade" tabindex="-1" role="dialog" id="data-modal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title">计划详情</h4>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" tabindex="-1" role="dialog" id="sucai-modal">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title">素材详情</h4>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function selectByParams(obj, type){
        var value = obj.options[obj.selectedIndex].value;
        // var url = isPlan ? '/index.php?r=site/ad-manage#plan' : '/index.php?r=site/ad-manage#sucai';
        var req_url = window.location.href;

        if(type === 'status'){
            if (req_url.match(/&tf_status=-?[0-9]*/)) {
                req_url = req_url.replace(/&tf_status=-?[0-9]*/,'&tf_status=' + value);
            } else {
                req_url = req_url.split('#')[0];
                req_url += '&tf_status=' + value + '#plan';
            }
        } else if (type === 'sc_status') {
            if (req_url.match(/&sc_status=-?[0-9]*/)) {
                req_url = req_url.replace(/&sc_status=-?[0-9]*/,'&sc_status=' + value);
            } else {
                req_url = req_url.split('#')[0];
                req_url += '&sc_status=' + value + '#sucai';
            }
        } else if (type === 'tf_type') {
            if (req_url.match(/&tf_type=-?[0-9]*/)) {
                req_url = req_url.replace(/&tf_type=-?[0-9]*/,'&tf_type=' + value);
            } else {
                req_url = req_url.split('#')[0];
                req_url += '&tf_type=' + value + '#plan';
            }
        }
        // var url = '/index.php?r=site/ad-manage';
        // url += type === 'status' ? '&tf_status='+value : '&tf_type='+value;
        // url += isPlan ? '#plan' : '#sucai';
        location.href = req_url;
    }

    function selectCourseByPlan(obj) {
        var value = obj.options[obj.selectedIndex].value;
        var req_url = window.location.href;
        if (req_url.match(/&sel_plan=[0-9]*/)) {
            req_url = req_url.replace(/&sel_plan=[0-9]*/,'&sel_plan=' + value);
        } else {
            req_url = req_url.split('#')[0];
            req_url += '&sel_plan=' + value + '#sucai';
        }

        location.href = req_url;
    }

    function planModalShow() {
        // alert('---');
        $('#plan-modal').on('shown.bs.modal', function () {
            $('#myInput').focus()
        })
    }

    $(function () {
        var req_url = window.location.href;
        if (req_url) {
            var arrUrl = req_url.split('#');
            var postfix = arrUrl.pop();
            if (arrUrl.length>=1 && postfix == 'sucai') {
                // console.log($('#sucai'));
                // $('#plan_li').removeClass("active");
                $('#sucai_li').addClass("active");
                $('#sucai-panel').addClass("active");
            } else {
                $('#plan_li').addClass("active");
                $('#plan-panel').addClass("active");
                //     $('#sucai_li').removeClass("active");
            }
        }
        $('#data-modal').on('show.bs.modal', function (e) {
            var link = $(e.relatedTarget);
            var title_info = link.data('whatever');
            $(this).find(".modal-title").text(title_info + "详情");
            $(this).find(".modal-body").load(link.attr("href"));
        });
        $('#data-modal').on('hidden.bs.modal', function (e) {
            // location.reload()
        });
        $('#sucai-modal').on('show.bs.modal', function (e) {
            var link = $(e.relatedTarget);
            $(this).find(".modal-body").load(link.attr("href"));
        });
        $('#sucai-modal').on('hidden.bs.modal', function (e) {
            // location.reload()
        });

        $('#sucai_li').click(function(){
            location.href = req_url.split("#")[0] + "#sucai";
        });
        $('#plan_li').click(function(){
            location.href = req_url.split("#")[0] + "#plan";
        });
    });

    function onClickPlanCheckBox(obj){
        var set_tf_url = "/index.php?r=ad-plans/set-tf";
        if(obj.checked){
            var r=confirm("确定开启投放？");
            if (r!=true) {
                alert("取消投放!");
                obj.checked = false;
            } else {
                set_tf_url += '&plan_id=' + obj.id.substring('plan_'.length) + "&is_tf=1"
                // console.log(obj.id)
                // console.log(set_tf_url)
                $.ajax(set_tf_url, {
                    dataType: 'json'
                }).done(function (data) {
                    if (data.code == 0) {
                        alert(data.msg);
                        obj.checked = false;
                    } else if (data.code == 1) {
                        alert(data.msg);
                    }
                }).fail(function (xhr, status) {
                    alert('失败: ' + xhr.status + ', 原因: ' + status)
                    obj.checked = false;
                });
            }
        }else{
            var r=confirm("确定取消投放？");
            if (r==true) {
                set_tf_url += '&plan_id='+obj.id.substring('plan_'.length) + "&is_tf=0"
                $.ajax(set_tf_url, {
                    dataType: 'json'
                }).done(function (data) {
                    if (data.code == 0) {
                        alert(data.msg);
                        obj.checked = false;
                    } else if (data.code==1) {
                        alert(data.msg);
                    }
                }).fail(function (xhr, status) {
                    alert('失败: ' + xhr.status + ', 原因: ' + status)
                    obj.checked = false;
                });
            } else {
                alert('放弃取消投放');
                obj.checked = true;
            }
        }
        location.reload();
    }

    function onClickCourseCheckBox(obj){
        var set_tf_url = "/index.php?r=courses/set-tf";
        if(obj.checked){
            var r=confirm("确定开启投放？");
            if (r!=true) {
                alert("取消投放!");
                obj.checked = false;
            } else {
                set_tf_url += '&course_id=' + obj.id.substring('course_'.length) + "&is_tf=1"
                // console.log(obj.id)
                // console.log(set_tf_url)
                $.ajax(set_tf_url, {
                    dataType: 'json'
                }).done(function (data) {
                    if (data.code == 0) {
                        alert(data.msg);
                        obj.checked = false;
                    } else if (data.code == 1) {
                        alert(data.msg);
                    }
                }).fail(function (xhr, status) {
                    alert('失败: ' + xhr.status + ', 原因: ' + status)
                    obj.checked = false;
                });
            }
        }else{
            var r=confirm("确定取消投放？");
            if (r==true) {
                set_tf_url += '&course_id='+obj.id.substring('course_'.length) + "&is_tf=2"
                $.ajax(set_tf_url, {
                    dataType: 'json'
                }).done(function (data) {
                    if (data.code == 0) {
                        alert(data.msg);
                        obj.checked = false;
                    } else if (data.code==1) {
                        alert(data.msg);
                    }
                }).fail(function (xhr, status) {
                    alert('失败: ' + xhr.status + ', 原因: ' + status)
                    obj.checked = false;
                });
            } else {
                alert('放弃取消投放');
                obj.checked = true;
            }
        }
        location.reload();
    }
</script>
