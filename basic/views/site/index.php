<?php

/* @var $this yii\web\View */
use yii\widgets\LinkPager;

$this->title = '竞价系统';
?>

<script src="/js/echarts.common.min.js"></script>
<script type="text/javascript">
    $(function(){
        var flag = '<?php echo $flag;?>';
        console.log(flag);
        if (flag == 'today') {
            $("input[name='btn_time']").removeClass("btn-primary");
            $($("input[name='btn_time']")[0]).addClass("btn-primary");
        }
        if (flag == 'yestoday') {
            $("input[name='btn_time']").removeClass("btn-primary");
            $($("input[name='btn_time']")[1]).addClass("btn-primary");
        }
        if (flag == 'week') {
            $("input[name='btn_time']").removeClass("btn-primary");
            $($("input[name='btn_time']")[2]).addClass("btn-primary");
        }


        $("#begin_time").change(function(){
            var begin_time = $(this).val()
            var end_time = $("#end_time").val()
            if (begin_time != '' && end_time != '') {
                if (begin_time<=end_time) {
                    var req_url = location.href;
                    if (req_url.match(/index\.php\?r=site/) == null) {
                        req_url = req_url + '/index.php?r=site';
                    }
                    if (req_url.match(/&begin_time=(\d{4}-\d{1,2}-\d{1,2})+&end_time=(\d{4}-\d{1,2}-\d{1,2})+/)) {
                        req_url = req_url.replace(/&begin_time=(\d{4}-\d{1,2}-\d{1,2})+&end_time=(\d{4}-\d{1,2}-\d{1,2})+/,
                            '&begin_time=' + begin_time + '&end_time=' + end_time);
                    } else {
                        req_url += '&begin_time=' + begin_time + '&end_time=' + end_time;
                    }
                    console.log(req_url);
                    location.href = req_url
                } else {
                    alert("提示：日期选择不恰当！");
                }
            }
        });
        $("#end_time").change(function(){
            var begin_time = $("#begin_time").val()
            var end_time = $(this).val()
            if (begin_time != '' && end_time != '') {
                if (begin_time<=end_time) {
                    var req_url = location.href;
                    if (req_url.match(/index\.php\?r=site/) == null) {
                        req_url = req_url + '/index.php?r=site';
                    }
                    if (req_url.match(/&begin_time=(\d{4}-\d{1,2}-\d{1,2})+&end_time=(\d{4}-\d{1,2}-\d{1,2})+/)) {
                        req_url = req_url.replace(/&begin_time=(\d{4}-\d{1,2}-\d{1,2})+&end_time=(\d{4}-\d{1,2}-\d{1,2})+/,
                            '&begin_time=' + begin_time + '&end_time=' + end_time);
                    } else {
                        req_url += '&begin_time=' + begin_time + '&end_time=' + end_time;
                    }
                    console.log(req_url);
                    location.href = req_url
                } else {
                    alert("提示：日期选择不恰当！");
                }
            }
        });

        $("#btn-export").click(function(){
            var req_url = location.href;
            location.href = req_url + '&export=1';
        });
    });


    var http_url = '/index.php?r=site/user-datas';
    $(function(){
        // getUserData(http_url);
    });

    function selectByTime(e, time) {
        var req_url = location.href;
        console.log(req_url.match(/index\.php\?r=site/) === null)
        if (req_url.match(/index\.php\?r=site/) == null) {
            req_url = req_url + '/index.php?r=site';
        }
        var begin_time = '';
        var end_time = '';
        var day = new Date();
        if (time === 'today') {
            begin_time = format(day);
            end_time = format(day);
        } else if (time === 'yestoday') {
            day.setDate(day.getDate() - 1);
            begin_time = format(day);
            end_time = format(day);
        } else if (time === 'week') {
            day.setDate(day.getDate() - 1);
            end_time = format(day);
            day.setDate(day.getDate() - 6);
            begin_time = format(day);
        }

        if (req_url.match(/&begin_time=(\d{4}-\d{1,2}-\d{1,2})+&end_time=(\d{4}-\d{1,2}-\d{1,2})+/)) {
            req_url = req_url.replace(/&begin_time=(\d{4}-\d{1,2}-\d{1,2})+&end_time=(\d{4}-\d{1,2}-\d{1,2})+/,
                '&begin_time=' + begin_time + '&end_time=' + end_time);
        } else {
            req_url += '&begin_time=' + begin_time + '&end_time=' + end_time;
        }
        // console.log(req_url);
        // getUserData(http_url+'&begin_time=' + begin_time + '&end_time='+end_time);
        location.href = req_url

    }

    function format(date) {
        return date.getFullYear() + '-' + (date.getMonth()>8 ?'':'0') + (date.getMonth()+1) +'-'+ date.getDate()
    }

    function getUserData(url){
        var tbody = document.getElementById("sucai-huizong");
        $.ajax(url, {
            dataType: 'json'
        }).done(function (data) {
            if (data.data) {
                var str = "<tr>";
                var his_data = data.data.his_data;
                // var to_data = data.data.today_data;
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
            if (value != '-1') {
                url_index += '&plan_id=' + value;
            }
        }
        if (type == 2) {
            if (value != '-1') {
                url_index += '&c_id=' + value;
            }
        }

        location.href = url_index;
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
                </select>
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
        <div class="form-group col-lg-12">
            <label  class="col-lg-2 control-label">时间：</label>
            <div class="col-lg-8">
                <input class="btn btn-default col-lg-1" name="btn_time" type="button" value="今日" onclick="selectByTime(this, 'today')">
                <input class="btn btn-default col-lg-1" name="btn_time" type="button" value="昨日" onclick="selectByTime(this, 'yestoday')">
                <input class="btn btn-default col-lg-1" name="btn_time" type="button" value="近7日" onclick="selectByTime(this, 'week')">
                <div class="col-lg-3" style="padding: 0px;">
                    <input type="date" value="<?php echo $begin_time;?>" id="begin_time" class="form-control">
                </div>
                <div class="col-lg-3" style="padding: 0px;">
                    <input type="date" value="<?php echo $end_time;?>" id="end_time" class="form-control">
                </div>
            </div>
        </div>
        <!--        TODO 修改js  -->
        <!--  图表div  -->
        <div class="form-group col-lg-12" >
            <fieldset class="col-lg-offset-1 col-lg-11">
            <!--            <div class="col-lg-2">-->
            <!--                <select id="select_plan" class="col-lg-3 form-control">-->
            <!--                    <option>展示数</option>-->
            <!--                    <option>点击量</option>-->
            <!--                    <option>预约率</option>-->
            <!--                    <option>点击率</option>-->
            <!--                    <option>点击预约率</option>-->
            <!--                </select>-->
            <!--            </div>-->
            <div id="DayChart" style="width: 100%;height:260px;">

            </div>

            </fieldset>
        </div>
        <script type="text/javascript">
            var xAxis = <?php echo json_encode($echart_data['xaxis']);?>;
            // console.log(xAxis);
            // var xAxis = ["0:00","1:00","2:00","3:00","4:00","5:00","6:00","7:00","8:00","9:00","10:00","11:00","12:00",
            //     "13:00","14:00","15:00","16:00","17:00","18:00","19:00","20:00","21:00","22:00","23:00","24:00"];
            var show_num = <?php echo json_encode($echart_data['show_num']);?>;
            var click_num = <?php echo json_encode($echart_data['click_num']);?>;
            var book_num = <?php echo json_encode($echart_data['book_num']);?>;
            var click_rate = <?php echo json_encode($echart_data['click_rate']);?>;
            var book_rate = <?php echo json_encode($echart_data['book_rate']);?>;
            var click_book_rate = <?php echo json_encode($echart_data['click_book_rate']);?>;

            // 基于准备好的dom，初始化echarts实例
            var myChart = echarts.init(document.getElementById('DayChart'));
            //
            // // 指定图表的配置项和数据
            var option = {
                xAxis: {
                    name: '时间',
                    data: xAxis,
                },
                legend: {
                    data:['展示数', '点击数','点击率','预约数', '预约率','点击预约率']
                },
                yAxis: [
                    {
                        type: 'value',
                        name: '数量',
                        axisLabel: {
                            formatter: '{value}'
                        }
                    },
                    {
                        type : 'value',
                        name : '百分率',
                        axisLabel : {
                            formatter: '{value} %'
                        }
                    }
                ],
                series: [{
                    name: "展示数",
                    data: show_num,
                    // data: [820, 932, 901, 934, 1290, 1330, 1320],
                    type: 'bar',
                    itemStyle : { normal: {label : {show: true}}},
                    // color: "red"
                },{
                    name: "点击数",
                    data: click_num,
                    // data: [82, 93, 901, 934, 1290, 1330, 1320],
                    type: 'bar',
                    itemStyle : { normal: {label : {show: true}}},
                },{
                    name: "点击率",
                    data: click_rate,
                    // data: [0.42, 0.093, 0.0901, 0.0934, 0.1290, 0.1330, 0.1320],
                    type: 'line',
                    yAxisIndex: 1,
                    itemStyle : { normal: {label : {show: true}}},
                },{
                    name: "预约数",
                    data: book_num,
                    // data: [82, 93, 901, 934, 1290, 1330, 1320],
                    type: 'bar',
                    itemStyle : { normal: {label : {show: true}}},
                },{
                    name: "预约率",
                    data: book_rate,
                    // data: [0.20, 0.0932, 0.0901, 0.0934, 0.1290, 0.1330, 0.320],
                    type: 'line',
                    yAxisIndex: 1,
                    itemStyle : { normal: {label : {show: true}}},
                },{
                name: "点击预约率",
                    data: click_book_rate,
                    // data: [0.10, 0.0832, 0.0701, 0.0734, 0.1290, 0.2330, 0.220],
                    type: 'line',
                    yAxisIndex: 1,
                    itemStyle : { normal: {label : {show: true}}},
                }]
            };

            // 使用刚指定的配置项和数据显示图表。
            myChart.setOption(option);

            // myChart.showLoading();
            // $.get("/index.php?r=site/get-data").done(function(data, status){
            //     myChart.hideLoading();
            //     // console.log('status:' + status);
            //     // console.log(data);
            //     // console.log(data.data);
            //     myChart.setOption({
            //         // title: {
            //         //     text: '变化曲线'
            //         // },
            //         xAxis: {
            //             data: data.data.time
            //         },
            //         series: [{
            //             name: "展示数",
            //             type:'line',
            //             data: data.data.num
            //             },{
            //             name: "预约数",
            //             type:'bar',
            //             data: data.data.num
            //         }]
            //     });
            // });

        </script>
        <div class="col-lg-offset-1">
            <table class="table table-striped table-bordered">
            <caption>
                <input type="button" id="btn-export" class="col-lg-offset-11 btn btn-default" value="导出数据"></input>
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
<!--                <tr>-->
<!--                    --><?php
//                    foreach ($data['sum_data'] as $sum_data) {
//                        echo "<td>" . $sum_data . "</td>";
//                    }
//                    ?>
<!--                </tr>-->
                <?php
                    foreach ($data['datas'] as $data) {
                        echo "<tr>";
                        if ($flag == 'today') {
                            echo "<td>".$data['hour']."</td>";
                        } else {
                            echo "<td>".$data['date_at']."</td>";
                        }
                        echo "<td>".$data['show_num']."</td>";
                        echo "<td>".$data['click_num']."</td>";
                        echo "<td>".$data['click_rate']."</td>";
                        echo "<td>".$data['book_num']."</td>";
                        echo "<td>".$data['book_rate']."</td>";
                        echo "<td>".$data['click_book_rate']."</td>";
                        echo "<td>".$data['day_cost']."</td>";
                        echo "</tr>";
                    }
                ?>
            </tbody>
        </table>
        </div>
        <?= LinkPager::widget(['pagination' => $page,
            'nextPageLabel' => '下一页',
            'prevPageLabel' => '上一页',
            ]) ?>
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
