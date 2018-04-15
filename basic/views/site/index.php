<?php

/* @var $this yii\web\View */
use yii\widgets\LinkPager;

$this->title = '竞价系统';
?>

<script src="/js/echarts.common.min.js"></script>
<script type="text/javascript">
    var http_url = '/index.php?r=site/user-datas';
    $(function(){
        // getUserData(http_url);
    });
    // function redirectByPlan(obj) {
    //     var value = obj.options[obj.selectedIndex].value;
    //     var url = http_url + '&plan_id=' + value;
    //     getUserData(url);
    // }
    // function redirectByCourse(obj) {
    //     var value = obj.options[obj.selectedIndex].value;
    //     var url = http_url + '&c_id=' + value;
    //     getUserData(url);
    // }

    function selectByTime(time) {
        var begin_time = '';
        var end_time = '';
        if (time === 'today') {
            begin_time = '';
        } else if (time === 'yestoday') {
            begin_time = '';
        } else if (time === 'week') {
            begin_time = '';
        }
        // console.log(http_url+'&begin_time=' + begin_time + '&end_time='+end_time);
        // getUserData(http_url+'&begin_time=' + begin_time + '&end_time='+end_time);

    }

    function getUserData(url){
        var tbody = document.getElementById("sucai-huizong");
        $.ajax(url, {
            dataType: 'json'
        }).done(function (data) {
            if (data.data) {
                var str = "<tr>";
                var his_data = data.data.his_data;
                var to_data = data.data.today_data;
                var sum_data = data.data.sum_data;
                var page_size = data.data.page_size;
                var page_num = data.data.page_num;
                // console.log(sum_data);
                // console.log(his_data);
                for (i in sum_data) {
                    str += "<td>" + sum_data[i] + "</td>";
                }
                str += "</tr>";
                for (i in his_data) {
                    str += "<tr>" +
                        "<td>" + his_data[i].date_at + "</td>" +
                        "<td>" + his_data[i].show_num + "</td>" +
                        "<td>" + his_data[i].click_num + "</td>" +
                        "<td>" + his_data[i].click_rate + "</td>" +
                        "<td>" + his_data[i].book_num + "</td>" +
                        "<td>" + his_data[i].book_rate + "</td>" +
                        "<td>" + his_data[i].day_cost + "</td>" +
                        "</tr>";
                }
                str += '<nav aria-label="Page navigation">' +
                    '<ul class="pagination" id="pages">' +
                    '    <li><a href="' + http_url + "&page_num=" + (page_num-1) + '" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>';

                // str +=  '<li><a href="#">1</a></li><li>';
                for (var p=1;p<=page_size;p++) {
                    // console.log(p);
                    str +=  '<li><a href="' + http_url + "&page_num=" + p +'">'+ p +'</a></li><li>';
                }

                str +='<a href="'+http_url + "&page_num=" + (page_num+1) + '" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li></ul><nav>';


                tbody.innerHTML = str;
            }
        }).fail(function (xhr, status) {
            alert('失败: ' + xhr.status + ', 原因: ' + status);
        });
    }

    function redirectByParam(obj, type){
        // 1->plan；2->course
        var url_index = '/index.php?r=site/index';
        var value = obj.options[obj.selectedIndex].value;
        if (type == 1) {
            var url = url_index + '&plan_id=' + value;
        }
        if (type == 2) {
            var value = obj.options[obj.selectedIndex].value;
            var url = url_index + '&c_id=' + value;
        }

        location.href = url;
    }

</script>
<div class="site-index">
    <form class="form-horizontal">
        <div class="form-group col-lg-12">
            <label for="" class="col-lg-2 control-label">广告计划：</label>
            <div class="col-lg-3">
                <select id="select_plan" class="form-control" onchange="redirectByParam(this, 1)">
                    <?php foreach ($plans as $item): ?>
                        <option value="<?php echo $item['id'];?>" <?php if ($item['id'] === $sel_plan) {echo 'selected="selected"';} ?> >
                            <?php echo $item['plan_name']; ?>
                        </option>
                    <?php endforeach; ?>
<!--                    --><?php //foreach ($plans as $plan) {
//                        echo "<option value=".'"'.$plan['id'].'"'.">" . $plan['plan_name'] . "</option>";
//                    }
//                    ?>
                </select>
            </div>
            <label  class="col-lg-2 control-label">广告素材：</label>
            <div class="col-lg-3">
                <select id="select_course" class="form-control" onchange="redirectByParam(this, 2)">
                    <?php foreach ($courses as $item): ?>
                        <option value="<?php echo $item['id'];?>" <?php if ($item['id'] === $sel_course) {echo 'selected="selected"';} ?> >
                            <?php echo $item['ad_sc_title']; ?>
                        </option>
                    <?php endforeach; ?>
<!--                    --><?php //foreach ($courses as $course) {
//                        echo "<option value=".'"'.$course['id'].'"'.">" . $course['ad_sc_title'] . "</option>";
//                    }
//                    ?>
                </select>
            </div>
        </div>
        <div class="form-group col-lg-12">
            <label  class="col-lg-2 control-label">投放时间：</label>
            <div class="col-lg-8">
                <input class="btn btn-default" type="button" value="今日" onclick="selectByTime('toady')">
                <input class="btn btn-default" type="button" value="昨日" onclick="selectByTime('yestoday')">
                <input class="btn btn-default" type="button" value="本周" onclick="selectByTime('week')">
<!--                <input type="date" value="" onclick="selectByTime('begin_time')">-->
<!--                <input type="date" value="" onclick="selectByTime('end_time')">-->
            </div>
        </div>
        <div class="form-group col-lg-12">
            <fieldset class="col-lg-offset-1 col-lg-9">
                <legend><label>广告统计<label style="font-size: 15px;">(当日广告素材投放汇总)</label></label></legend>
                <div class="form-group col-lg-12">
                    <div class="col-lg-4 x-sidebar-right">
                        <label>投放中：</label>
                        <label><?= $data['tfz'] ?></label>
                    </div>
                    <div class="col-lg-4 x-sidebar-right">
                        <label>待审核：</label>
                        <label><?= $data['dsk'] ?></label>
                    </div>
                    <div class="col-lg-4" style="text-align: center">
                        <label>待投放：</label>
                        <label><?= $data['dtf'] ?></label>
                    </div>
                </div>
            </fieldset>
        </div>
        <!--        TODO 修改js  -->
        <!--  图表div  -->
        <div id="DayChart" class="" style="width: 100%;height:200px;"></div>
        <script type="text/javascript">
            // 基于准备好的dom，初始化echarts实例
            var myChart = echarts.init(document.getElementById('DayChart'));

            // 指定图表的配置项和数据
            var option = {
                title: {
                    text: '实时预约率变化曲线'
                },
                // tooltip: {},
                legend: {
                    data:['预约率']
                },
                xAxis: {
                    data: ["0:00","1:00","2:00","3:00","4:00","5:00","6:00","7:00","8:00","9:00","10:00","11:00","12:00",
                        "13:00","14:00","15:00","16:00","17:00","18:00","19:00","20:00","21:00","22:00","23:00","24:00"]
                },
                yAxis: {},
                series: [{
                    name: '预约率',
                    type: 'line',
                    data: []
                }]
            };

            // 使用刚指定的配置项和数据显示图表。
            myChart.setOption(option);
            myChart.showLoading();
            $.get("/index.php?r=site/get-data").done(function(data, status){
                myChart.hideLoading();
                // console.log('status:' + status);
                // console.log(data);
                // console.log(data.data);
                myChart.setOption({
                    xAxis: {
                        data: data.data.time
                    },
                    series: [{
                        name: "预约率",
                        data: data.data.num
                    }]
                });
            });

        </script>
        <div class="col-lg-offset-1" style="position: relative;top: 180px;">
            <table class="table table-striped table-bordered">
            <caption>
<!--                <label class="col-lg-2 control-label">投放时间：</label>-->
<!--                <div class="col-lg-10">-->
<!--                    <button class="btn btn-default" onclick="selectByTime('')">今日</button>-->
<!--                    <button class="btn btn-default" onclick="selectByTime()">昨日</button>-->
<!--                    <button class="btn btn-default" onclick="selectByTime()">本周</button>-->
<!--                </div>-->
                <input type="button" class="col-lg-offset-11 btn btn-default" value="导出数据"></input>
            </caption>
            <thead>
                <tr>
                    <?php
                        foreach($data['tableTitles'] as $title){
                            echo "<th>" . $title  ."</th>";
                        }
                    ?>
                </tr>
            </thead>
            <tbody id="sucai-huizong">
                <tr>
                    <?php
                    foreach ($data['sum_data'] as $sum_data) {
                        echo "<td>" . $sum_data . "</td>";
                    }
                    ?>
                </tr>
                <?php
                    foreach ($data['his_data'] as $his_data) {
                        echo "<tr>";
                        echo "<td>".$his_data['date_at']."</td>";
                        echo "<td>".$his_data['show_num']."</td>";
                        echo "<td>".$his_data['click_num']."</td>";
                        echo "<td>".$his_data['click_rate']."</td>";
                        echo "<td>".$his_data['book_num']."</td>";
                        echo "<td>".$his_data['book_rate']."</td>";
                        echo "<td>".$his_data['day_cost']."</td>";
                        echo "</tr>";
                    }
                ?>
            </tbody>
        </table>
        </div>
        <div style="position: relative;top: 150px;">
        <?= LinkPager::widget(['pagination' => $page,
            'nextPageLabel' => '下一页',
            'prevPageLabel' => '上一页',
            ]) ?>
        </div>

        <?php
        /*
        if($page_size>=1): ?>
        <div class="col-lg-offset-1" style="position: relative;top: 150px;">
            <nav aria-label="Page navigation">
                <ul class="pagination">
                    <li>
                        <a href="<?php echo explode('&page_num',$_SERVER['REQUEST_URI'])[0].'&page_num='. ($page_num-1>0 ? $page_num-1 : 1); ?>" aria-label="Previous">
<!--                        <a href="--><?php //echo '/index.php?r=site/index'.(isset($sel_plan)?'&plan_id='.$sel_plan:'').(isset($sel_course)?'&c_id='.$sel_course:'').'&page_num='. ($page_num-1>0 ? $page_num-1 : 1); ?><!--" aria-label="Previous">-->
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                    <?php for ($i=1; $i<= $page_size; $i++){
                        echo '<li><a href="' . explode('&page_num',$_SERVER['REQUEST_URI'])[0].'&page_num=' . $i .  '">'. $i .'</a></li>';
//                        echo '<li><a href="' . '/index.php?r=site/index'.(isset($sel_plan)?'&plan_id='.$sel_plan:'').(isset($sel_course)?'&c_id='.$sel_course:'').'&page_num=' . $i .  '">'. $i .'</a></li>';
                    } ?>
                    <li>
                        <a href="<?php echo explode('&page_num',$_SERVER['REQUEST_URI'])[0].'&page_num='. ($page_num+1<$page_size ? $page_num+1 : $page_size); ?>" aria-label="Next">
<!--                        <a href="--><?php //echo '/index.php?r=site/index'.(isset($sel_plan)?'&plan_id='.$sel_plan:'').(isset($sel_course)?'&c_id='.$sel_course:'').'&page_num='. ($page_num+1<$page_size ? $page_num+1 : $page_size); ?><!--" aria-label="Next">-->
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
        <?php endif
        */
        ?>

    </form>
</div>
