Уважаемый, <?php echo $fullName;?>
<br/><br/>
О Вас написал(а) <?php echo CHtml::encode($senderFullName);?> новый отзыв:<br/>
<blockquote>
    <?php echo CHtml::encode($message);?>
</blockquote>