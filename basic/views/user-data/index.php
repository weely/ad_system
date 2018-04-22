<?php
/* @var $this yii\web\View */

$this->context->layout = false;
?>
<table class="table table-striped table-bordered">
    <caption>
        <div class="col-lg-5">
            <label>日期：<?=$date_at ?></label>
        </div>
        <div class="col-lg-offset-10">
            <a class="btn btn-primary" href="index.php?r=user-data/day-datas&date_at=<?=$date_at?>&export=1" id="btn-day-export">导出数据</a>
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
            <td><?php echo $item['title']; ?></td>
            <td><?php echo $item['mobile']; ?></td>
            <td><?php echo $item['name']; ?></td>
            <td><?php echo $item['gender']; ?></td>
            <td><?php echo $item['city']; ?></td>
            <td><?php echo $item['age']; ?></td>
            <td><?php echo $item['position']; ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<script>
$(function(){
    // $("#btn-day-export").click(function(){
    //
    //     location.href = req_url;
    // });
});

</script>