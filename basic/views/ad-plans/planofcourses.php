<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\AdPlansSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Ad Plans';
//$this->params['breadcrumbs'][] = $this->title;
$this->context->layout = false;

?>
<div class="ad-plans-index row">
    <div class="col-lg-4">
        <ul id="" class="nav nav-pills nav-stacked"">
            <?php foreach ($courses as $item): ?>
                <li class="<?php if($sucai_id == $item['id']){echo "active";} ?>">
                    <a href="#<?php echo $item['id']; ?>" role="tab" data-toggle="tab"><span><?php echo $item['id'].$item['ad_sc_title']; ?></span></a></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <div class="col-lg-8 tab-content">
        <?php foreach ($courses as $item): ?>
            <div role="tabpanel" class="tab-pane <?php if($sucai_id == $item['id']){echo "active";} ?>" id="<?php echo $item['id']; ?>">
<!--                <div> --><?php //echo $item['ad_sc_title']; ?><!--</div>-->
<!--                TODO 展示待确定-->
                <div style="">
                    <h4 style="text-align: center">广告位预览</h4>
                    <?php if ($item['is_h5']=='0'): ?>
                    <div id="sc_preview" style="background: rgba(42,171,210,0.24);padding: 10px;margin-left: 42px;margin-right: 42px;" class="row">
                        <h5><?=$item['ad_sc_title']?></h5>
                        <div class="col-lg-3" style="padding: 0px;">
                            <img src="<?php echo $item['logo']; ?>" style="width: 70px;height: 70px;"></div>
                        <div class="col-lg-9">
                            <div>
                                <?php foreach (explode(',',$item['properties']) as $tag): ?>
                                    <span style="font-size: 14px;"><?php echo $tag; ?></span>
                                <?php endforeach; ?>
                            </div>
                            <div style="margin-bottom: 5px;margin-top: 2px;">
                                <?php foreach (explode(',',$item['tags']) as $tag): ?>
                                    <span style="font-size: 14px;"><?php echo $tag; ?></span>
                                <?php endforeach; ?>
                            </div>
                            <div>
                                <span style="font-size: 14px;"><?=Yii::$app->user->getIdentity('"_identity":"yii\web\User"')['showname']?></span>
                                <span style="border:1px solid #4ba7f4;color: #4ba7f4;font-size: 14px;">智联教育</span>
                            </div>
                        </div>
                    </div>
                    <?php elseif($item['is_h5']=='1'): ?>
                        <div style="margin-left: 42px;margin-right: 42px;">
                            <img src="<?php echo $item['title_img'];?>" style="width: 270px;height: 150px;">
                        </div>
                    <?php endif; ?>
                </div>
                <div style="text-align: center">
                    <h4>H5预览</h4>
                    <img src="<?php echo $item['img_html']; ?>" style="width:280px;height:360px;">
                </div>

            </div>
        <?php endforeach; ?>
    </div>

</div>
