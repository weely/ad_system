<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Courses */
/* @var $form yii\widgets\ActiveForm */
?>
<link rel="stylesheet" type="text/css" href="/css/preview.css">
<script>
    $(function(){
        if ($("input[name='is_h5']:checked").val()=='0'){
            $("#is_show").css('display','');
            $("#upload-tp-title").css('display','none');
            $("#sc_preview_wz").css('display','');
            $("#sc_preview_tp").css('display','none');
        } else if($("input[name='is_h5']:checked").val()=='1'){
            $("#is_show").css('display','none');
            $("#upload-tp-title").css('display','');
            $("#sc_preview_wz").css('display','none');
            $("#sc_preview_tp").css('display','');
        }
        $("input[name='is_h5']").click(function(){
            if ($(this).val() == '0'){
                $("#is_show").css('display','');
                $("#upload-tp-title").css('display','none');
                $("#sc_preview_wz").css('display','');
                $("#sc_preview_tp").css('display','none');
            } else if ($(this).val() == '1') {
                $("#is_show").css('display','none');
                $("#upload-tp-title").css('display','');
                $("#sc_preview_wz").css('display','none');
                $("#sc_preview_tp").css('display','');
            }
        });
        // $("input[name='is_online']").click(function(){
        //     console.log($(this).val());
        // });
        if ($("input[name='h5']:checked").val()=='0'){
            $('#h5_link').hide();
            $('.img_pre').show();
            $('.link_pre').hide();
        } else if ($("input[name='h5']:checked").val()=='1') {
            $('#h5_link').show();
            //打开链接预览
            $('.link_pre').show();
            $('.img_pre').hide();
        }

        $("input[name='h5']").click(function(){
            if($(this).val()=='1'){
                //打开链接输入框
                $('#h5_link').show();
                //打开链接预览
                $('.link_pre').show();
                $('.img_pre').hide();
                
                //关掉落地页大图相关的按钮编辑区域
                $('#resetBtnWords .compileArea').addClass('hidden');
                $('.collapseBtn').addClass('hidden');
            }else if($(this).val()=='0'){
                $('#h5_link').hide();
                $('.img_pre').show();
                $('.link_pre').hide();
            }
        });
        /**
         * 广告标题上传
         */
        $("#upload-title-img").click(function(e){
            e.preventDefault();
            $("#file-title-upload").click();
        });
        $("#file-title-upload").change(function () {
            // $('#loading').modal('show');
            var formData = new FormData();
            formData.append('file', $("#file-title-upload")[0].files[0]);
            formData.append('file_name', "title");
            $.ajax({
                url: "index.php?r=courses/upload-file",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function (data) {
                    if (data.status == "true") {
                        // alert(data.msg);
                        // $("input[name='title_img']").val(data.msg.slice(0,-4));
                        // $("#img_title_src").attr('src', data.msg.slice(0,-4));
                        $("input[name='title_img']").val(data.msg);
                        $("#img_title_src").attr('src', data.msg);
                    }
                    if (data.status == "error") {
                        alert(data.msg);
                    }
                    // $('#loading').modal('hide');
                },
                error: function () {
                    alert("上传失败！");
                    // $('#loading').modal('hide');
                }
            });
        });
        /**
         * 广告logo上传
         */
        $("#upload-logo").click(function(e){
            e.preventDefault();
            $("#file-logo-upload").click();
        });
        $("#file-logo-upload").change(function () {
            // $('#loading').modal('show');
            var formData = new FormData();
            formData.append('file', $("#file-logo-upload")[0].files[0]);
            formData.append('file_name', 'logo')
            $.ajax({
                url: "index.php?r=courses/upload-file",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function (data) {
                    if (data.status == "true") {
                        // alert(data.msg);
                        // $("input[name='logo']").val(data.msg.slice(0,-4));
                        // $("#img_logo_src").attr('src', data.msg.slice(0,-4));
                        $("input[name='logo']").val(data.msg);
                        $("#img_logo_src").attr('src', data.msg);
                    }
                    if (data.status == "error") {
                        alert(data.msg);
                    }
                    // $('#loading').modal('hide');
                },
                error: function () {
                    alert("上传失败！");
                    // $('#loading').modal('hide');
                }
            });
        });
        /**
         * 广告副文本编辑上传
         */
        $("#upload-edit").click(function(e){
            e.preventDefault();
            $("#file-edit-upload").click();
        });
        $("#file-edit-upload").change(function () {
            // $('#loading').modal('show');
            var formData = new FormData();
            formData.append('file', $("#file-edit-upload")[0].files[0]);
            formData.append('file_name', 'edit')
            $.ajax({
                url: "index.php?r=courses/upload-file",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function (data) {
                    if (data.status == "true") {
                        alert(data.msg);
                        // $("input[name='edit']").val(data.msg.slice(0,-4));
                        // $("#file-edit-upload").val(data.msg);
                    }
                    if (data.status == "error") {
                        alert(data.msg);
                    }
                    // $('#loading').modal('hide');
                },
                error: function () {
                    alert("上传失败！");
                    // $('#loading').modal('hide');
                }
            });
        });
        /**
         * 广告H5上传
         */
        $("#upload-H5").click(function(e){
            e.preventDefault();
            $("#file-H5-upload").click();
        });
        $("#file-H5-upload").change(function () {
            // $('#loading').modal('show');
            var formData = new FormData();
            formData.append('file', $("#file-H5-upload")[0].files[0]);
            formData.append('file_name', 'h5')
            $.ajax({
                url: "index.php?r=courses/upload-file",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function (data) {
                    if (data.status == "true") {
                        // $("#modal-body").val(data.msg);
                        // alert("上传成功");
                        // $("input[name='img_html']").val(data.msg.slice(0,-4));
                        $("input[name='img_html']").val(data.msg);
                        $("#img_html_src").attr('src', data.msg);
                    }
                    if (data.status == "error") {
                        // $("#modal-body").val(data.msg);
                        alert(data.msg);
                    }
                    // $('#loading').modal('hide');
                },
                error: function () {
                    alert("上传失败！");
                    // $('#loading').modal('hide');
                }
            });
        });

        // $('#loading').on('show.bs.modal', function (event) {
        //     // $(this).find('.modal-title').text('New message to ' + recipient)
        //     // $(this).find('.modal-body input').val(recipient)
        //     $("#modal-body").val();
        // })

        $("#cancel").click(function(){
            location.href = '/index.php?r=site/ad-manage#sucai';
        });

    });
    function check(){
        if($("#title").val() == ''){
            alert("广告素材标题为空");
            $("#title").focus();
            return false;
        }

        if ($("input[name='is_h5']:checked").val()=='0'){
            if ($("#properties").val() == '' || $("#properties").val().length>26) {
                alert("！输入广告特点不符要求");
                $("#properties").focus();
                return false;
            }
            if ($("#tags").val() == '' || $("#tags").val().length>26) {
                alert("！输入广告标签不符要求");
                $("#tags").focus();
                return false;
            }

            if ($("input[name='logo']").val() == '' || typeof($("input[name='logo']").val()) == 'undefined') {
                alert("！请上传LOGO");
                $("#file-logo-upload").focus();
                return false;
            }
        }
        if ($("input[name='is_h5']:checked").val()=='1'){
            if ($("input[name='title_img']").val() == '' || typeof($("input[name='title_img']").val()) == 'undefined') {
                alert("！请上传图片素材");
                $("#file-logo-upload").focus();
                return false;
            }
        }
        if ($("input[name='img_html']").val() == '' || typeof($("input[name='img_html']").val()) == 'undefined') {
            alert("！请上传落地页图片素材");
            $("#file-H5-upload").focus();
            return false;
        }

        return true;
    }
</script>

<form class="courses-form form-horizontal col-lg-8" action="/index.php?r=courses/save" method="post" onsubmit="return check()">
    <input name="_csrf" type="hidden" id="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
    <input name="id" type="hidden" value="<?=$model->id?>">
    <div class="form-group">
        <label class="form-label col-lg-3">广告素材目标计划:</label>
        <div class="col-lg-5">
            <select id="sel_plan" name="plan_id" class="form-control">
                <?php foreach ($plans as $item): ?>
                    <option value="<?php echo $item['id']; ?>"><?php echo $item['plan_name'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <div class="form-group" >
        <label class="form-label col-lg-3">广告标题:</label>
        <div class="col-lg-9" >
            <input type="text" id="title" value="<?=$model['ad_sc_title']?>" name="sc_title" placeholder="请输入标题" class="form-control">
        </div>
    </div>
    <div class="form-group">
        <label class="form-label col-lg-3">广告类型:</label>
        <div class="col-lg-9">
            <div class="form-group">
                <label class="form-label col-lg-2">
                    <input type="radio" name="is_online" value="1" <?php echo explode(',', $model['properties'])[0] == '线上'?'checked':''; ?>>线上</label>
            </div>
            <div class="form-group">
                <label class="form-label col-lg-2">
                    <input type="radio" name="is_online" value="0" <?php echo explode(',', $model['properties'])[0] == '线上'?'':'checked'; ?>>线下</label>
            </div>
        </div>
    </div>
    <hr>
    <div class="form-group">
        <label class="form-label col-lg-3">Card类型:</label>
        <div class="col-lg-9">
            <div class="form-group">
                <label class="form-label col-lg-2">
                    <input type="radio" name="is_h5" value="0" checked>文字</label>
                <label class="form-label col-lg-2">
                    <input type="radio" name="is_h5" value="1" <?php echo $model->is_h5=='1' ? 'checked' : '' ?>>图片</label>
                <div class="col-lg-8" id="upload-tp-title" style="display: none">
                    <div style="position: relative;">
                        <input type="button" value="上传" id="upload-title-img" class="btn btn-default" style="display: block;
                        position: absolute;z-index: 1;">
                        <input type="file" id="file-title-upload" style="display: none;position: absolute;z-index: 5;opacity:0;">
                        <input type="hidden" name="title_img" value="<?=$model['title_img']?>">
                    </div>
                    <span class="col-lg-offset-2" style="color: #365550;font-size: 12px;">png、jpg格式,710px*220px,大小不超过100k</span>
                </div>
            </div>
        </div>
    </div>
        <!--    <div class="form-group">-->
        <!--        <label class="form-label col-lg-3">广告种类:</label>-->
        <!--        <div class="col-lg-9">-->
        <!--            <div class="form-group">-->
        <!--                <div class="col-lg-4">-->
        <!--                    <select id="tag_ids" name="tag_ids" class="form-control">-->
        <!--                        <option value="1">北京</option>-->
        <!--                    </select>-->
        <!--                </div>-->
        <!--            </div>-->
        <!--        </div>-->
        <!--    </div>-->
    <div id="is_show" style="display: none;">
        <div class="form-group">
            <label class="form-label col-lg-3">广告特点:</label>
            <div class="col-lg-9">
                <div class="form-group">
                    <input type="text" id="properties" name="properties" value="<?=$model['properties']?>" placeholder="例如：线上,80课时" class="form-control">
                    <span style="color: #365550;font-size: small">2-3个标签，用英文格式的逗号','隔开，单个标签不超过8个字符</span>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label col-lg-3">广告标签:</label>
            <div class="col-lg-9">
                <div class="form-group">
                    <input type="text" id="tags" name="tags" value="<?=$model['tags']?>" placeholder="例如：免考,终生可查" class="form-control">
                    <span style="color: #365550;font-size: small">2-3个标签，用英文格式的逗号','隔开，单个标签不超过8个字符</span>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="form-label col-lg-3">LOGO:</label>
            <div class="col-lg-9">
                <div class="form-group">
                    <div style="position: relative;">
                        <input type="button" value="上传" id="upload-logo" class="btn btn-default" style="display: block;
                        position: absolute;z-index: 1;">
                        <input type="file" id="file-logo-upload" style="display: none;position: absolute;z-index: 5;opacity:0;">
                        <input type="hidden" name="logo" value="<?=$model['logo']?>">
                    </div>
                    <span class="col-lg-offset-2" style="color: #365550;font-size: small">png、jpg格式,710px*220px,大小不超过100k</span>
                </div>
            </div>
        </div>
    </div>
    <hr>
    <div class="form-group">
        <label class="form-label col-lg-3">H5:</label>
        <div class="col-lg-9">
            <div class="form-group">
                <label class="form-label col-lg-3">
                    <input type="radio" name="h5" value="0" <?php echo $model['is_link']=='0'?'checked':'' ?>>
                    落地页大图</label>
                <div class="col-lg-3" style="position: relative;">
                    <input type="button" value="上传" id="upload-H5" class="btn btn-default" style="display: block;
                    position: absolute;z-index: 1;">
                    <input type="file" id="file-H5-upload" style="display: none;position: absolute;z-index: 5;opacity:0;">
                    <input type="hidden" name="img_html" value="<?=$model['img_html']?>">
                </div>
            </div>
            <?php if ($model['tf_type'] == '3'): ?>
                <div class="form-group" style="border-top: 1px solid #ddd;padding-top: 10px;">
                    <label class="form-label col-lg-3">
                        <input type="radio" name="h5" value="1" <?php echo $model['is_link']=='1'?'checked':'' ?>>
                        落地页链接</label>
                    <input id="h5_link" class="form-control" style="display: none" type="text" name="img_html" value="<?=$model['img_html']?>" placeholder="请输入链接">
                </div>
            <?php elseif($model['tf_type'] == '4'): ?>
                <div id="resetBtnWords" class="form-group">
                    <input type="button" value="编辑按钮文案" class="btn btn-default">
                    <input type="button" class="collapseBtn hidden btn btn-default"  value="应用并收起">
                    <div class="hidden compileArea" style="border:1px solid #ddd;padding:10px;">
                        <p style="margin:10px 0;">点击前:</p>
                        <div class="btn-box box-item" style="padding-left: 20px">
                            <div class="footer-left">
                                <p class="row" style="margin: 0 0 10px;">
                                    <input id="btn_7" class="col-lg-3 form-control" type="" name="" value="价值" style="width:30%">
                                    <strong style="width:100px;overflow: hidden;display: block;padding:0;width:30%" class="money-num col-lg-3"><input maxlength="4" id="btn_4" type="" name="" value="288" class="form-control"></strong>
                                    <input id="btn_8" class="col-lg-3 form-control" type="" name="" value="元" style="width:30%">
                                </p>
                                <p class="course-type"><input class="form-control" id="btn_5" type="" name="" value="咨询会"></p>
                            </div>
                            <div class="footer-right">
                                <div class="right-bottom">
                                    <input class="form-control" id="btn_6" type="" name="" value="立即0元抢">
                                </div>
                            </div>
                        </div>
                        <p style="margin:10px 0;">点击后:</p>
                        <div class="succeed-box box-item"  style="padding-left: 20px">
                            <div class="succeed-text-box">
                                <p>
                                    <strong id="compellation"><input class="form-control" id="btn_1" type="" name="" value="您好"></strong><br>
                                    <input class="form-control" id="btn_2" type="" name="" value="，您已成功获得咨询会礼包,学校老师会通过"><br>
                                    <strong id="uphone">18888888888</strong><br>
                                    <input class="form-control" id="btn_3" type="" name="" value="联系您">
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif ?>
           <!--  <div class="form-group">
                <label class="form-label col-lg-3">
                    <input type="radio" name="h5" value="3">
                    H5源文件</label>
                <div class="col-lg-3" style="position: relative;">
                    <input type="button" value="上传" id="" class="btn btn-default" style="display: block;
                    position: absolute;z-index: 1;">
                    <input type="file" id="" style="display: none;position: absolute;z-index: 5;opacity:0;">
                    <input type="hidden" name="" value="">
                </div>
            </div> -->
        </div>
    </div>

    <div class="form-group">
        <div class="col-lg-offset-3">
            <input type="submit" value="提交审核" class="btn btn-primary">
            <input type="button" id="cancel" value="取  消" class="btn btn-default">
        </div>
    </div>
</form>

<div id="preview" class="col-lg-4" style="min-width: 375px;">
    <!--    广告位预览-->
    <div>
        <h4 style="text-align: center">广告位预览</h4>
        <div id="sc_preview_wz" style="background: rgba(42,171,210,0.24);padding: 10px;" class="row">
            <h5 class="title_pre" >请输入标题</h5>
            <div class="col-lg-3" style="padding: 0px;">
                <img id="img_logo_src" src="<?php echo $model['logo'];?>" style="width: 70px;height: 70px;"></div>
            <div class="col-lg-9">
                <div id="properties_pre" style="height: 20px">
                    <span style="font-size: 14px;">线上</span>
                    <span style="font-size: 14px;">可是</span>
                </div>
                <div id="tags_pre" style="margin-bottom: 5px;margin-top: 2px;height: 20px">
                    <span style="font-size: 14px;">免考</span>
                    <span style="font-size: 14px;">终身可查</span>
                </div>
                <div>
                    <span style="font-size: 14px;">英孚英语</span>
                    <span style="border:1px solid #4ba7f4;color: #4ba7f4;font-size: 14px;">智联教育</span>
                </div>
            </div>
        </div>
        <div id="sc_preview_tp" style="display: none;height:130px;overflow: hidden;background:rgb(230,230,230) url(imgs/logo.png) no-repeat center">
            <img id="img_title_src" src="<?php echo $model['title_img'];?>" style="width: 100%;">
        </div>
    </div>
    <hr>
    <!--    H5大图预览-->
    <div id="sc_html" style="text-align: center;">
        <h5>H5预览</h5>

        <div class="img_pre" style="position: relative;">
            <p class="title_pre">请输入标题</p>
            <div style="position: relative;width:375px;height: 630px;overflow-y: scroll;">
                <img id="img_html_src" src="<?php echo $model['img_html'] ?>" style=" width: 100%;height: auto;">
            </div>
        </div>

        <?php if($model['tf_type'] == '3'): ?>
        <div class="link_pre" style="display: none">
            <p class="title_pre">请输入标题</p>
            <iframe style="width:375px;height: 630px;overflow-y: scroll;" src="<?=$model['img_html']?>"></iframe>
        </div>
        <?php elseif ($model['tf_type'] == '4'): ?>
        <!-- 显示按钮-->
        <footer id="footer-first" style="position: absolute;">
            <div class="apply-box">
                <ul style="top: 0px;">
                    <li>
                        <span>云女士</span>
                        <span>177****8907</span>
                        <span>2分钟前</span>
                    </li>
                </ul>
            </div>
            <div class="btn-box box-item">
                <div class="footer-left">
                    <p><span id="btn_7_pre">价值</span><strong class="money-num" id="btn_4_pre">288</strong><span id="btn_8_pre">元</span></p>
                    <p class="course-type" id="btn_5_pre">咨询会</p>
                </div><div class="footer-right">
                    <div class="right-top">
                        <p class="apply-text">已有<strong class="people-num">425</strong>人报名</p>
                        <p class="timer">55 : 12 : 01</p>
                    </div>
                    <div class="right-bottom">
                        <button class="submit-btn" id="btn_6_pre">立即0元抢</button>
                    </div>
                </div>
            </div>
        </footer>
        <?php endif;?>
        <div class="footer-em">了解更多课程信息，请下载 <span class="text-em">名校MBA指南</span> APP</div>
        <div class="contact-us"><p>智联教育广告投放，请联系Ada：15901822548</p></div>
        <div style="height: 146px;"></div>

        <footer id="footer-first" class="footer-second"  style="position: relative;display:none;">
            <div class="succeed-box box-item" style="display: block">
                <div class="succeed-text-box">
                    <p><strong id="compellation"><span id="btn_1_pre">您好</span></strong><span id="btn_2_pre">您已成功获得咨询会礼包，学校老师会通过</span><strong id="uphone">18888888888</strong><span id="btn_3_pre">联系您</span></p>
                </div>
            </div>
        </footer>
    </div>
</div>

<div class="modal fade" id="loading" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop='static'>
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">提示</h4>
            </div>
            <div class="modal-body" id="modal-body">
                图片上传中。。。
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    //设置预览：
    var preview={
        ids:['btn_1','btn_2','btn_3','btn_4','btn_5','btn_6','btn_7','btn_8','title'],
        setProperties:function(){
            var vs=$('#properties')[0].value.split(',');
            if(vs.length==1&&!vs[0]){
                $("#properties_pre").html('');
                return;
            };
            var htm='';
            vs.map(function(v){
                htm+=' <span style="font-size: 14px;">'+v+'</span> ';
            });
            $("#properties_pre").html(htm?htm:e.target.placeholder);
        },
        setTags:function(){
            var vs=$('#tags')[0].value.split(',');
            if(vs.length==1&&!vs[0]){
                $("#tags_pre").html('');
                return;
            };
            var htm='';
            vs.map(function(v){
                htm+=' <span style="font-size: 14px;">'+v+'</span> ';
            });
            $("#tags_pre").html(htm?htm:e.target.placeholder);
        },
        setBtn:function(i){
            var ids=this.ids;
            var ele=$("#"+ids[i])[0];
            var v=ele.value;
            $("#"+ids[i]+"_pre").html(v?v:ele.placeholder);
            if(ids[i]=='title'){
                $("."+ids[i]+"_pre").html(v?v:ele.placeholder);
            };
            if(ids[i]=='btn_4'){
                if(!v){
                    $('#footer-first .footer-left .money-num').addClass('noBefore');
                }else{
                    $('#footer-first .footer-left .money-num').removeClass('noBefore');
                }
            }
        },
        bindChange:function(i){
            var that=this;
            $("#"+this.ids[i]).bind("input propertychange change",function(e){
                that.setBtn(i);
            });
        },
        bind:function(){
            $("#properties").bind("input propertychange change",function(e){
                preview.setProperties();
            });
            $("#tags").bind("input propertychange change",function(e){
                preview.setTags();        
            });
            $('#h5_link').bind("input propertychange change",function(e){
                $('.link_pre iframe').attr('src',e.target.value);
            });
            $('#resetBtnWords input').click(function(e){
                $('#resetBtnWords .compileArea').removeClass('hidden');
                $('.collapseBtn').removeClass('hidden').click(function(){
                    $('#resetBtnWords .compileArea').addClass('hidden');
                    $('.collapseBtn').addClass('hidden');
                });
            });

            for (var i = 0; i<this.ids.length; i++) {
                this.bindChange(i);
            };
        },

        init:function(){
            this.bind();
            this.setProperties();
            this.setTags();
            for (var i = 0; i<this.ids.length; i++) {
                this.setBtn(i);
            };
        },
    };
    preview.init();
</script>