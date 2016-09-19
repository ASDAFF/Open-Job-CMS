<?php
$sender = $review->sender;
?>

<div class="well">
    <div class="message_header">
        <div class="message_top_line">
            <?php echo CHtml::link($sender->username, $sender->getUrl()); ?>
            <span><?php echo HDate::getSmart(strtotime($review->date_created));?></span>
        </div>
    </div>

    <div class="clear"></div>

    <?php
    $sender->renderAva();
    ?>

    <div class="message_body">
        <div class="bubble me <?php echo $review->getCssClass();?>">
            <p><?php echo CHtml::encode($review->text);?></p>
        </div>
    </div>

    <div class="clear"></div>
</div>