<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Courses */
/* @var $form yii\widgets\ActiveForm */
?>

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
        // $("input[name='is_h5']").click(function(){
        //     console.log($(this).val());
        // });
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
                        alert("上传成功");
                        // $("input[name='img_html']").val(data.msg.slice(0,-4));
                        $("input[name='img_html']").val(data.msg);
                        $("#img_img_html_src").attr('src', data.msg);
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

<form class="courses-form form-horizontal col-lg-9" action="/index.php?r=courses/save" method="post" onsubmit="return check()">
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
            <!--    <input type="button" name="img_name" value="上传" class="btn btn-default">-->
            <!--    <span style="color: #365550;font-size: small">png、jpg格式,200px*200px</span>-->
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
                    <input type="radio" name="h5" value="1">
                    落地页大图</label>
                <div class="col-lg-3" style="position: relative;">
                    <input type="button" value="上传" id="upload-H5" class="btn btn-default" style="display: block;
                    position: absolute;z-index: 1;">
                    <input type="file" id="file-H5-upload" style="display: none;position: absolute;z-index: 5;opacity:0;">
                    <input type="hidden" name="img_html" value="<?=$model['img_html']?>">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label col-lg-3">
                    <input type="radio" name="h5" value="1">
                    H5源文件</label>
                <div class="col-lg-3" style="position: relative;">
                    <input type="button" value="上传" id="" class="btn btn-default" style="display: block;
                    position: absolute;z-index: 1;">
                    <input type="file" id="file-H5-upload" style="display: none;position: absolute;z-index: 5;opacity:0;">
                    <input type="hidden" name="img_html" value="<?=$model['img_html']?>">
                </div>
            </div>
        </div>
    </div>


    <div class="form-group">
        <div class="col-lg-offset-3">
            <input type="submit" value="提交审核" class="btn btn-primary">
            <input type="button" id="cancel" value="取  消" class="btn btn-default">
        </div>
    </div>
</form>

<div class="col-lg-3">
    <div>
        <h4 style="text-align: center">广告位预览</h4>
        <div id="sc_preview_wz" style="background: rgba(42,171,210,0.24);padding: 10px;" class="row">
            <h5><?=$model['ad_sc_title']?></h5>
            <div class="col-lg-3" style="padding: 0px;">
                <img id="img_logo_src" src="<?php echo $model['logo'];?>" style="width: 70px;height: 70px;"></div>
            <div class="col-lg-9">
                <div>
                    <?php foreach (explode(',',$model['properties']) as $tag): ?>
                        <span style="font-size: 14px;"><?php echo $tag; ?></span>
                    <?php endforeach; ?>
                </div>
                <div style="margin-bottom: 5px;margin-top: 2px;">
                    <?php foreach (explode(',',$model['tags']) as $tag): ?>
                        <span style="font-size: 14px;"><?php echo $tag; ?></span>
                    <?php endforeach; ?>
                </div>
                <div>
                    <span style="font-size: 14px;"><?=Yii::$app->user->getIdentity('"_identity":"yii\web\User"')['showname']?></span>
                    <span style="border:1px solid #4ba7f4;color: #4ba7f4;font-size: 14px;">智联教育</span>
                </div>
            </div>
        </div>
        <div id="sc_preview_tp" style="display: none">
            <img id="img_title_src" src="<?php echo $model['title_img'];?>" style="width: 270px;height: 150px;">
        </div>
    </div>
<!--    <div id="sc_html" style="text-align: center">-->
<!--        <h5>H5预览</h5>-->
<!--        <img src="model->img_html" style="width: 270px;height: 360px;">-->
<!--    </div>-->
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