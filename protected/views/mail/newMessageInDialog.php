Уважаемый, <?php echo $fullName;?>
<br/><br/>
Вам написал(а) <?php echo CHtml::encode($senderFullName);?>:<br/>
<blockquote>
    <?php echo CHtml::encode($message);?>
</blockquote>