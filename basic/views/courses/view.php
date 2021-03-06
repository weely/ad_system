<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Courses */

$this->title = "素材详情";

$this->context->layout = false;
?>

<div class="courses-view">
    <div>
        <div style="text-align: center">
            <h4 style="text-align: center">广告位预览</h4>
            <?php if ($model['is_h5']=='0'): ?>
            <div style="background: rgba(42,171,210,0.24);padding: 10px;margin-left: 135px;margin-right: 135px;" class="row">
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
            <?php elseif($model['is_h5']=='1'): ?>
            <div style="margin-left: 135px;margin-right: 135px;">
                <img src="<?php echo $model['title_img'];?>" style="width: 270px;height: 150px;">
            </div>
            <?php endif; ?>
        </div>

        <div id="sc_html" style="text-align: center">
            <h5>H5预览</h5>
            <iframe style="width:375px;height: 630px;overflow-y: scroll;" src="<?=$model['img_html']?>"></iframe>
            <!--<img src="--><?php //echo $model->img_html ?><!--" style="width: 270px;height: 400px;">-->
        </div>
    </div>
</div>
