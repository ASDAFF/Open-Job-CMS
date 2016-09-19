<?php if(Yii::app()->user->id){ ?>
<div class="user-view-action">
    <a class="btn btn-info" href="javascript:;" onclick="$('#review_form').show(); $(this).hide();"><?php echo $this->buttonText;?></a>
</div>

<div id="review_form" <?php if(!$review->errors): ?>style="display: none;"<?php endif; ?>>
    <div class="form">

        <?php
        $form=$this->beginWidget('BUserActiveForm', array(
            'id'=>'review-form',
            'enableAjaxValidation'=>false,
        ));

        echo $form->textAreaRow($review, 'text', array('class' => 'width500 height100'));

        if(Yii::app()->user->id != $this->recipient->id){
            echo $form->radioButtonListRow($review, 'type', Review::getTypeList());
        }

        echo '<div class="clear"></div>';

        $this->widget('bootstrap.widgets.TbButton', array(
            'buttonType'=>'submit',
            'label'=>$this->buttonText,
            'type'=>'success',
            'icon'=>'icon-plus-sign icon-white',
        ));

        $this->endWidget();

        ?>
    </div>
</div>
<?php } ?>

<?php
if ($reviews) {
    foreach ($reviews as $review) {
        $this->render('_review_body', array(
            'review' => $review,
        ));
    }
}
?>

<div class="pagination">
<?php
$this->widget('ReverseLinkPager', array(
    'pages'=>$pages,
));
?>
</div>
