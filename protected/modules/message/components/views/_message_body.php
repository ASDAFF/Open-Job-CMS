<?php
/**
 * @var $message Message
 * @var $sender User
 * @var $this WMessage
 * @var Project $project
 */

//$message = $data;

$sender = $message->sender;

if(Yii::app()->user->id && $message->sender_id != Yii::app()->user->id && $message->viewed == 0){
    $message->viewed = 1;
    $message->update(array('viewed'));
}
?>
<div class="well message">

<div class="message_header">
    <div class="message_top_line">
        <?php echo CHtml::link($sender->username, $sender->getUrl()); ?>
        <span><?php echo HDate::getSmart(strtotime($message->date_created));?></span>
    </div>
</div>

<div class="clear"></div>

<?php
$sender->renderAva();
?>

<div class="message_body">
    <div class="bubble me">
        <p><?php echo CHtml::encode($message->body);?></p>
    </div>
</div>

<div class="clear"></div>

    <?php
    $links = array();

    if($message->sender_id != Yii::app()->user->id && $canWrite){
        $links[] = CHtml::link('Ответить', 'javascript:;', array('onclick' => 'mess.answer('.CJavaScript::encode($message->sender->username).')'));
    }

    if($message->object_name != 'Dialog' && !$message->isISender()){
        $links[] = CHtml::link('Личное сообщение', Dialog::getUrlWithUser($message->sender_id));
    }

    if($project){
        $iProjectOwner = $project->owner->id == Yii::app()->user->id;

        if( $iProjectOwner
            && $project->status == Project::STATUS_OPEN
            && !$project->executor_id && Yii::app()->user->id != $message->sender_id
        ){
            $links[] = CHtml::link('Выбрать исполнителем',
                Yii::app()->createUrl('/project/setExecutor',
                    array('projectID' =>$project->id, 'userID' => $message->sender_id)),
                array('onclick' => 'if (!confirm("Выбрать исполнителем?")) return false;')
            );
        }

        if($project->executor_id == $message->sender_id){
            $links[] = '<b>Выбран исполнителем</b>';

            if($iProjectOwner){
                $links[] = CHtml::link('Отклонить исполнителя',
                    Yii::app()->createUrl('/project/unsetExecutor', array('projectID' =>$project->id, 'userID' => $message->sender_id)),
                    array('onclick' => 'js: if(!confirm("Отклонить исполнителя?")) return false;')
                );
            }
        }
    }

    if($links){
    ?>
    <div class="message_bottom_line">
        <?php echo implode(' | ', $links);;?>
    </div>
<?php } ?>

</div>

