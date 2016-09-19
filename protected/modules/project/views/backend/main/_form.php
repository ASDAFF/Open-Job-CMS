<?php
/* @var $this ProjectController */
/* @var $model Project */
/* @var $form BUserActiveForm */
?>

<div class="form">

    <?php $form=$this->beginWidget('BUserActiveForm', array(
        'id'=>'project-_form-form',
        'enableAjaxValidation'=>false,
    )); ?>

    <p class="note"><?php echo Yii::t('main', 'req_fields'); ?></p>

    <?php echo $form->errorSummary($model); ?>

    <?php
    if(Yii::app()->user->isGuest){
        require '_form_guest.php';
    }
    ?>

    <?php if(!$model->isNewRecord): ?>
        <div class="row">
            <?php echo $form->labelEx($model,'status'); ?>
            <?php echo $form->dropDownList($model,'status', Project::getStatusList()); ?>
            <?php echo $form->error($model,'status'); ?>
        </div>
    <?php endif; ?>

    <div class="row">
        <?php echo $form->labelEx($model,'title'); ?>
        <?php echo $form->textField($model,'title', array('class'=>'span6')); ?>
        <?php echo $form->error($model,'title'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'description'); ?>

        <?php
        $this->widget('BaseEditor', array(
            'model' => $model,
            'attribute' => 'description',
        ));
        ?>
        <?php echo $form->error($model,'description'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'budget'); ?>

        <?php echo $form->checkBoxRow($model, 'budget_agreement');?>
        <div id="Project_budget_box" <?php if($model->budget_agreement) echo 'style="display: none;"'; ?>>
            <?php echo $form->textField($model,'budget', array('class'=>'span3')) . '&nbsp' . Project::getCurrencyName(); ?>
        </div>
        <?php echo $form->error($model,'budget'); ?>
    </div>

    <div class="row">
        <?php echo $form->dropDownListRow($model, 'skillsSave', Skill::getCategoriesListData(), array('multiple' => 'multiple', 'class' => 'span3'));?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'payment_type'); ?>
        <?php echo $form->dropDownList($model,'payment_type', Project::getPayTypeArray(), array('class'=>'span3')); ?>
        <?php echo $form->error($model,'payment_type'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'date_open_until'); ?>
        <?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
            'model' => $model,
            'attribute' => 'date_open_until',
            'language' => 'ru',
            'options' => array(
                'showAnim' => 'fold',
                'height' => '300px'
            ),
            'htmlOptions' => array(
                'class' => 'span3'
            ),
        ));?>
        <?php echo $form->error($model,'date_open_until'); ?>
    </div>

    <?php if (Yii::app()->user->isGuest){ ?>
        <div class="row">
            <?php echo $form->labelEx($model, 'verifyCode'); ?>
            <?php echo $form->textField($model, 'verifyCode'); ?>

            <?php $this->widget('CCaptcha', array(
                'imageOptions' => array('class' => 'captchaImg')
            )); ?>
            <?php echo $form->error($model, 'verifyCode'); ?>

            <p class="hint"><?php echo UserModule::t("Please enter the letters as they are shown in the image above."); ?>
                <br/><?php echo UserModule::t("Letters are not case-sensitive."); ?></p>
        </div>
    <?php } ?>

    <div class="row buttons">
        <?php $this->widget('bootstrap.widgets.TbButton', array(
            'label'=>$model->isNewRecord ? 'Добавить' : 'Сохранить',
            'type'=>'primary',
            'buttonType'=>'submit',
        )); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->

<?php $this->widget( 'ext.EChosen.EChosen', array(
    'target' => '#Project_skillsSave',
)); ?>

<script type="text/javascript">
    $('#Project_budget_agreement').click(
        function(){
            $this = $(this);

            if($this.is(':checked')){
                $('#Project_budget_box').slideUp();
                $('#Project_budget').val('');
            } else {
                $('#Project_budget_box').slideDown();
            }
        }
    );
</script>
