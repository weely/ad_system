<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

//$this->title = '意向用户';
//$this->params['breadcrumbs'][] = $this->title;
?>
<script>
    $(function(){
        var today = new Date();

        var calendar = {
            year: today.getFullYear(),
            click_year: 2018,
            diff: 1,
            totlaMonth: 12,
            click_item: null,           //calendar对象点击的span元素
            click_date: today.getDate(),
            datas: <?php echo json_encode($datas);?>,
            html:"",
            getDaysInMonth: function(year, month){
                month = parseInt(month,10)+1;
                var days = new Date(year,month,0);
                return days.getDate();
            },
            loadCalendar: function(){
                this.html = '<div class="calendar-container container-fluid">\n' +
                    '        <div class="form-group col-lg-12" style="padding-top: 10px">\n' +
                    '            <label class="form-label col-lg-1">年份：</label>';
                for (var i=this.year-this.diff;i<=this.year;i++) {
                    this.html += '<div class="col-lg-1 col-sm-3">' +
                        '<input type="button" id="year_'+ i +'" onclick="dataByYear(this)" class="form-control" value="'+i+'"></div>';
                }
                this.html += '<div class="col-lg-offset-4 col-lg-5" style="">\n' +
                    '  <label class="form-label col-lg-1" style="color: gray">\n' +
                    '     <input type="radio" name="radio_tf_type" value="1"  disabled>CPM</label>\n'+
                    '  <label class="form-label col-lg-1" style="color: gray">\n'+
                    '     <input type="radio" name="radio_tf_type" value="2" disabled>CPC</label>\n'+
                    '    <label class="form-label col-lg-1">\n'+
                    '       <input type="radio" name="radio_tf_type" value="3" '+ (this.datas.tf_type==3 ? "checked":"") +'>CPA</label>\n'+
                    '    <label class="form-label col-lg-1">\n'+
                    '       <input type="radio" name="radio_tf_type" value="4" '+ (this.datas.tf_type==4 ? "checked":"") +'>CPL</label>\n'+
                    '    <label class="form-label col-lg-1" style="color: gray">\n'+
                    '        <input type="radio" name="radio_tf_type" value="5" disabled>CPS</label>\n'+
                    '  </div>' +'</div>' +
                    '<div class="row">';
                for (var j=1;j<=this.totlaMonth;j++) {
                    this.html += '<div class="col-lg-4 col-sm-6 calendar-item"><h5 class="calendar-title">'+ j +
                        '月</h5><div class="col-lg-12" style="padding: 0px;">';
                    for(var t=1; t<=this.getDaysInMonth(this.year,j,0); t++) {
                        var date = this.year+'-'+(j>9?'':'0')+j+'-'+(t>9?'':'0')+t;
                        var show_number = typeof(calendar.datas[date])!='undefined' ? calendar.datas[date] : 0;
                        this.html += '<span href="index.php?r=user-data/day-datas&date_at='+date+'&tf_type='+ this.datas.tf_type +'" class="col-lg-1 calendar-date " ' +
                            'name="date-item" data-remote="false" data-target="#click-modal"><p class="item-date-number">'+ t +'</p>';
                        if (show_number > 0) {
                            this.html += '<p class="item-number">'+ show_number +'人</p></span>';
                        } else {
                            this.html += '<p class="item-gray-number">'+ show_number +'人</p></span>';
                        }
                    }
                    this.html += '</div></div>';
                }
                this.html += '</div></div>';
                $("#calendar").append(calendar.html);
                $("#year_"+calendar.click_year).addClass('btn-primary');
            }
        }
        calendar.click_year = <?php echo $year;?>+'';
        if (calendar.click_year == '') {
            calendar.click_year = today.getFullYear()
        }
        calendar.loadCalendar();

        // 每日点击事件
        $("span[name='date-item']").click(function(){
            var number = $($(this).children('p')[1]).html().replace('人','')
            calendar.click_item = $(this);
            if (parseInt(number)>0){
                $('#click-modal').modal('show');
            } else {
                $('#click-modal').modal('hide');
            }
        });
        // 每日点击事件弹出modal
        $('#click-modal').on('show.bs.modal', function () {
            var req_url = calendar.click_item.attr("href");
            // console.log(req_url);
            $(this).find(".modal-body").load(req_url);
        });
        // $('#click-modal').on('loaded.bs.modal', function (e) {
        //     // location.reload()
        // });
        $("input[name='radio_tf_type']").click(function(){
            var req_url = location.href
            var tf_type = $(this).val()
            if (req_url.match(/index\.php\?r=site\/intent-user/i) == null) {
                req_url = '/index.php?r=site/intent-user';
            }
            if (req_url.match(/&tf_type=[1-5]{0,1}/i)) {
                req_url = req_url.replace(/&tf_type=[1-5]{0,1}/, '&tf_type=' + tf_type);
            } else {
                req_url += '&tf_type=' + tf_type;
            }
            location.href = req_url
        });

    });

    function dataByYear(obj){
        var redirect_url = '/index.php?r=site/intent-user&year=' + $(obj).val()
        location.href = redirect_url
    }

</script>
<div class="site-contact">
    <h3><?= Html::encode($this->title) ?></h3>

    <div id="calendar" class="calendar-container">
    </div>

</div>
<!-- 弹出modal，点击每日查询的数据 -->
<div class="modal fade" id="click-modal" tabindex="-1" role="dialog" aria-labelledby="click-modal" data-backdrop='static'>
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">当日数据详情</h4>
            </div>
            <div class="modal-body" id="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

