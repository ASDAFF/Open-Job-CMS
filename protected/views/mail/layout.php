<?php echo $contentMail; ?>

<hr/>
<p>С уважением, <br/>
    администрация сайта
    <br/>
    <a target="_blank" href="<?php echo Yii::app()->createAbsoluteUrl('/')?>"><?php echo CHtml::encode(Yii::app()->name)?></a></p>
<small>Это письмо создано и отправлено автоматически, отвечать на него не нужно</small>