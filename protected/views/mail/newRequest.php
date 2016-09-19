Уважаемый, <?php echo $fullName;?>
<br/><br/>
На ваш заказ <?php echo $projectLink;?> пришла заявка от <?php echo CHtml::encode($senderFullName);?>:<br/>
<blockquote>
    <?php echo CHtml::encode($message);?>
</blockquote>