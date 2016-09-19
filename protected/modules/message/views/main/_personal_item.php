<?php
/**
 * @var $data Message
 * @var $sender User
 */

$sender = $data->sender;

if(Yii::app()->user->id && $data->sender_id != Yii::app()->user->id && $data->viewed == 0){
    $data->viewed = 1;
    $data->update('viewed');
}
?>

<div class="well message">

    <div class="message_header">
        <div class="message_top_line">
            <?php echo CHtml::link($sender->username, $sender->getUrl()); ?>
            <span><?php echo HDate::formatDate($data->date_created);?></span>
        </div>
    </div>

    <div class="clear"></div>

    <?php
    $sender->renderAva();
    ?>

    <div class="message_body">
        <div class="bubble me">
            <p><?php echo CHtml::encode($data->body);?></p>
        </div>
    </div>

    <div class="clear"></div>

</div>