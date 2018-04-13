<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Courses */
/* @var $form yii\widgets\ActiveForm */
?>
<script src="https://cdn.jsdelivr.net/npm/vue@2.5.16/dist/vue.js"></script>
<script>
    $(function(){
        // console.log($("input[name='radio_title']:checked").val());
        if ($("input[name='radio_title']:checked").val()=='wz'){
            $("#is_show").css('display','');
            $("#upload-tp-title").css('display','none');
            // $("#tp_title").css('display','none');
            // $("#wz_title").css('display','');
            // $("#tp_title").attr("disabled",true);
            // $("#wz_title").attr("disabled",true);
            // $("#wz_title").removeAttr("disabled");
        } else if($("input[name='radio_title']:checked").val()=='tp'){
            $("#is_show").css('display','none');
            $("#upload-tp-title").css('display','');
            // $("#tp_title").css('display','');
            // $("#wz_title").css('display','none');
            // $("#wz_title").attr("disabled",true);
            // $("#tp_title").attr("disabled",true);
            // $("#tp_title").removeAttr("disabled");
        }
        $("input[name='radio_title']").click(function(){
            if ($(this).val() == 'wz'){
                $("#is_show").css('display','');
                $("#upload-tp-title").css('display','none');
                // $("#tp_title").css('display','none');
                // $("#wz_title").css('display','');
                // $("#tp_title").attr("disabled",true);
                // $("#wz_title").removeAttr("disabled");
            } else if ($(this).val() == 'tp') {
                $("#is_show").css('display','none');
                $("#upload-tp-title").css('display','');
                // $("#tp_title").css('display','');
                // $("#wz_title").css('display','none');
                // $("#wz_title").attr("disabled",true);
                // $("#tp_title").removeAttr("disabled");
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
                        alert(data.msg);
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
                        alert(data.msg);
                        // $("#file-logo-upload").val(data.msg);
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
                        alert(data.msg);
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
        if ($("input[name='radio_title']:checked").val()=='wz'){
            if ($("#type_tags").val() == '' || $("#type_tags").val().length>26) {
                alert("！输入广告特点不符要求");
                $("#type_tags").focus();
                return false;
            }
            if ($("#tags").val() == '' || $("#tags").val().length>26) {
                alert("！输入广告标签不符要求");
                $("#tags").focus();
                return false;
            }
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

    <div class="form-group">
        <label class="form-label col-lg-3">广告标题:</label>
        <div class="col-lg-9">
            <div class="form-group">
                <label class="form-label col-lg-2">
                    <input type="radio" name="radio_title" value="wz" checked>文字</label>
                <label class="form-label col-lg-2">
                    <input type="radio" name="radio_title" value="tp" <?php echo $model->is_h5=='1' ? 'checked' : '' ?>>图片</label>
                <div class="col-lg-9" >
                    <input type="text" id="title" value="<?=$model['ad_sc_title']?>" name="sc_title" placeholder="请输入标题" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <div class="col-lg-8" id="upload-tp-title" style="display: none">
                    <div style="position: relative;">
                        <input type="button" value="上传" id="upload-title-img" class="btn btn-default" style="display: block;
                        position: absolute;z-index: 1;">
                        <input type="file" id="file-title-upload" name="title_img" value="<?=$model['title_img']?>" style="display: none;position: absolute;z-index: 5;opacity:0;">
                    </div>
                    <span class="col-lg-offset-2" style="color: #365550;font-size: small">png、jpg格式,710px*220px,大小不超过100k</span>
                </div>
            </div>
            <div class="form-group" >

            </div>
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
                <label class="form-label col-lg-2"><input type="radio" name="is_online" value="0" <?php echo explode(',', $model['properties'])[0] == '线下'?'checked':''; ?>>线下</label>
<!--                <div class="col-lg-4">-->
<!--                    <select id="sel_addr" name="sel_addr" class="form-control">-->
<!--                        <option value="1">北京</option>-->
<!--                    </select>-->
<!--                </div>-->
            </div>
        </div>
    </div>
    <div id="is_show" style="display: none;">
        <div class="form-group">
            <label class="form-label col-lg-3">广告特点:</label>
            <div class="col-lg-9">
                <div class="form-group">
                    <input type="text" id="type_tags" name="type_tags" value="<?=$model['properties']?>" placeholder="例如：线上,80课时" class="form-control">
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
                        <input type="file" id="file-logo-upload" name="logo" value="<?=$model['logo']?>" style="display: none;position: absolute;z-index: 5;opacity:0;">
                    </div>
                    <span class="col-lg-offset-2" style="color: #365550;font-size: small">png、jpg格式,710px*220px,大小不超过100k</span>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label class="form-label col-lg-3">H5:</label>
        <div class="col-lg-9">
        <!--            <div class="form-group">-->
        <!--                <label class="form-label col-lg-3"><input type="radio" name="is_h5" value="0" checked>副文本编辑框</label>-->
        <!--                <input type="button" value="上传" class="btn btn-default">-->
        <!--                <div class="col-lg-3" style="position: relative;">-->
        <!--                    <input type="button" value="上传" id="upload-edit" class="btn btn-default" style="display: block;-->
        <!--                    position: absolute;z-index: 1;">-->
        <!--                    <input type="file" id="file-edit-upload" name="edit_html" style="display: none;position: absolute;z-index: 5;opacity:0;">-->
        <!--                </div>-->
        <!--            </div>-->
            <div class="form-group">
                <label class="form-label col-lg-3"><input type="radio" name="is_h5" value="1">H5源文件</label>
<!--                <input type="button" value="上传" class="btn btn-default">-->
                <div class="col-lg-3" style="position: relative;">
                    <input type="button" value="上传" id="upload-H5" class="btn btn-default" style="display: block;
                    position: absolute;z-index: 1;">
                    <input type="file" id="file-H5-upload" name="img_html" value="<?=$model['img_html']?>" style="display: none;position: absolute;z-index: 5;opacity:0;">
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
        <div id="sc_preview" style="background: rgba(42,171,210,0.24);padding: 10px;" class="row">
            <h5><?=$model['ad_sc_title']?></h5>
            <div class="col-lg-3" style="padding: 0px;">
                <img src="<?php echo $model->logo; ?>" style="width: 70px;height: 70px;"></div>
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