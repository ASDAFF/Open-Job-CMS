<?php
/* @var $this ProjectController */
/* @var $newUser User */
/* @var $form BUserActiveForm */
?>
<style>
    div#iam {
        padding: 10px;
    }
    div.form-inline label.radio {
        display: inline-block !important;
        font-size: 17px;
        padding: 0 20px;
        width: 200px;
    }
    div.margin30 {
        margin-left: -30px !important;
    }
</style>

<?php
if($newUser->errors || $modelLogin->errors){
    echo $form->errorSummary(array($newUser, $modelLogin));
}
?>

<div class="row margin30">

    <div class="form-inline" id="iam">
        <?php
        echo $form->radioButtonList($newUser, 'iam', RegistrationForm::getIamList(), array('onchange' => 'checkIam()'))
        ?>
    </div>

    <div id="iam_reg">
        <div class="span3">
            <?php echo $form->textFieldRow($newUser, 'username', array('class'=>'span3')); ?>
            <?php echo $form->passwordFieldRow($newUser, 'password', array('class'=>'span3')); ?>
        </div>

        <div class="span3">
            <?php echo $form->textFieldRow($newUser, 'email', array('class'=>'span3')); ?>
            <?php echo $form->passwordFieldRow($newUser, 'verifyPassword', array('class'=>'span3')); ?>
        </div>
    </div>

    <div id="iam_login" style="display: none;">
        <div class="span3">
            <?php echo CHtml::activeLabelEx($modelLogin,'username'); ?>
            <?php echo CHtml::activeTextField($modelLogin,'username') ?>

            <?php echo CHtml::activeLabelEx($modelLogin,'password'); ?>
            <?php echo CHtml::activePasswordField($modelLogin,'password') ?>
        </div>

        <div class="span3">

        </div>
    </div>

</div>

<hr>

<script>
    $(function(){
        checkIam();
    });

    function checkIam(){
        var iam = $("#iam input:radio:checked").val();
        if(iam == <?php echo RegistrationForm::I_AM_NEW_USER ?>){
            $('#iam_reg').show();
            $('#iam_login').hide();
        }else{
            $('#iam_reg').hide();
            $('#iam_login').show();
        }
    }
</script>

