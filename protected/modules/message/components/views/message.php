<?php
/**
 * @var $message Message
 * @var $this WMessage
 * @var $form BUserActiveForm
 * @var $project Project
 */


$cs = Yii::app()->clientScript;
$cs->registerScriptFile(Yii::app()->baseUrl . '/js/jquery.autosize.min.js', CClientScript::POS_END);

$project = $this->objectName == 'Project' ? $this->object : NULL;

$canWrite = !$project || ($project && $project->status == Project::STATUS_OPEN);

if($canWrite && !$this->formBottom){
    if (!Yii::app()->user->isGuest) {
        $this->render('_form', array('message' => $message));
    }
}

if ($messages) {
	foreach ($messages as $mess) {
        // Если статус скрыт показываем только
        if($mess->canShow()){
            $this->render('_message_body', array(
                'message' => $mess,
                'canWrite' => $canWrite,
                'project' => $project,
            ));
        } else {
            continue;
        }
	}
}

echo '<div class="pagination">';
$this->widget('ReverseLinkPager', array(
    'pages'=>$pages,
));
echo '</div>';

if($canWrite && $this->formBottom){
    if (!Yii::app()->user->isGuest) {
        $this->render('_form', array('message' => $message));
    }
}

?>

<script type="text/javascript">
    $(function(){
        $('textarea').autosize();
    });

    var mess = {
        answer: function(name){
            var message = $('#Message_body');
            var messageText = message.val() + '@' + name + ' ';
            message.val(messageText);
            message.focus();
            $("html,body").animate({scrollTop: $('#message-div-form').offset().top}, 500);
            return;
        },
        personal: function(user_id){

        }
    }
</script>