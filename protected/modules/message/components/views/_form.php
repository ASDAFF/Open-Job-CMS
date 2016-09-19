<?php
/**
 * @var $form BUserActiveForm
 * @var $message Message
 * @var $this WMessage
 */

?>

<div class="form" id="message-div-form">

	<?php
	$form=$this->beginWidget('BUserActiveForm', array(
		'id'=>'message-form',
		'enableAjaxValidation'=>false,
	));

    echo $form->textAreaRow($message, 'body', array('class' => 'width500 height100'));

    if($this->showStatuses){
        echo $form->radioButtonListRow($message, 'status', Message::getStatusesList());
    }

	echo '<div class="clear"></div>';

	$this->widget('bootstrap.widgets.TbButton', array(
		'buttonType'=>'submit',
		'label'=>$this->buttonLabel,
		'type'=>'success',
		'icon'=>'icon-plus-sign icon-white',
	));

	$this->endWidget();

	?>
</div>