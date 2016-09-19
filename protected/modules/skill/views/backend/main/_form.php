<?php
/* @var $this ProjectController */
/* @var $model Project */
/* @var $form BUserActiveForm */

Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/jquery.synctranslit.min.js');
?>

<div class="form">

    <?php $form=$this->beginWidget('BUserActiveForm', array(
        'id'=>'project-_form-form',
        'enableAjaxValidation'=>false,
    )); ?>

    <p class="note"><?php echo Yii::t('main', 'req_fields'); ?></p>

    <?php echo $form->errorSummary($model); ?>

    <?php
    if($model->isNewRecord && empty($_POST['Skill'])){
        $model->status = Skill::STATUS_ACTIVE;
    }
    ?>
    <div class="row">
        <?php echo $form->labelEx($model,'status'); ?>
        <?php echo $form->dropDownList($model,'status', Skill::getStatusList()); ?>
        <?php echo $form->error($model,'status'); ?>
    </div>

    <div class="row" id="parent_row">
        <?php echo $form->labelEx($model,'parent_id'); ?>
        <?php echo $form->dropDownList($model,'parent_id', Skill::getParentList(true)); ?>
        <?php echo $form->error($model,'parent_id'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'name'); ?>
        <?php echo $form->textField($model,'name', array('class'=>'span6')); ?>
        <?php echo $form->error($model,'name'); ?>
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

    <br>

    <div class="row">
        <?php echo $form->labelEx($model,'alias'); ?>
        <p class="hint">Используется для формирования url</p>
        <?php echo $form->textField($model,'alias', array('class'=>'span6')); ?>
        <?php echo $form->error($model,'alias'); ?>
    </div>

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

    $(document).ready(function(){
        $("#Skill_name").syncTranslit({destination: "Skill_alias"});
    });
</script>
