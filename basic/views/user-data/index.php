<?php
/* @var $this yii\web\View */
use yii\widgets\LinkPager;

$this->context->layout = false;
?>
<div id="result">
    <table class="table table-striped table-bordered">
        <caption>
            <div class="col-lg-5">
                <label>日期：<?=$date_at ?></label>
            </div>
            <div class="col-lg-offset-10">
                <a class="btn btn-primary" href="index.php?r=user-data/day-datas&date_at=<?=$date_at?>&tf_type=<?=$tf_type?>&export=1"
                   id="btn-day-export">导出数据</a>
            </div>
        </caption>
        <thead>
        <tr>
            <?php
            foreach($titles as $title){
                echo "<th>" . $title  ."</th>";
            }
            ?>
        </tr>
        </thead>
        <tbody id="courses">
        <?php foreach ($datas as $item): ?>
            <tr>
                <?php foreach ($item as $k => $V): ?>
                    <td><?php echo $V; ?></td>
                <?php endforeach; ?>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?= LinkPager::widget(['pagination' => $page,
        'nextPageLabel' => '下一页',
        'prevPageLabel' => '上一页',
    ]) ?>

</div>

<script>
    $('.pagination a').click(function(){
        if($(this).parent().hasClass('active'))
            return false;
        $.ajax({
            type: 'POST',
            url: $(this).attr('href'),
            data: 'ajax=1',
            success: function(ret){
                $('#result').html(ret);
            }
        });
        return false;
    });
</script>
