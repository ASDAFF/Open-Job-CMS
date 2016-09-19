<?php
/**
 * @var BUserActiveForm $form
 * @var UserSkills $model
 */

?>

<div class="form">
    <?php $form=$this->beginWidget('BUserActiveForm', array(
        'id'=>'profile-form',
        'enableAjaxValidation'=>false,
        'htmlOptions' => array(
            'enctype'=>'multipart/form-data',
            'class'=>'well'
        ),
    )); ?>

    <p class="note"><?php echo UserModule::t('Fields with <span class="required">*</span> are required.'); ?></p>

        <?php
        echo $form->errorSummary($model);

        echo $form->dropDownListRow($model, 'skill_id', Skill::getCategoriesListData(Yii::app()->user->id));
        echo $form->dropDownListRow($model, 'level', UserSkills::getLevelList());
        echo $form->dropDownListRow($model, 'experience', UserSkills::getExperienceList());

        echo CHtml::hiddenField('add_more', 0);

        //        $skillsAcList = Skill::getListForAutocomplete();
        //        $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
        //            'name' => 'skill',
        //            'value' => '',
        //            'source' => $skills,
        //            'options' => array(
        //                'showAnim' => 'fold',
        //                'minLength' => 0,
        //            ),
        //        ));
        ?>

    <div class="row buttons">
        <?php $this->widget('bootstrap.widgets.TbButton', array(
            'label'=> 'Сохранить',
            'type'=>'info',
            'icon'=>'ok white',
            'buttonType'=>'submit',
        ));

        echo '&nbsp;';

        $this->widget('bootstrap.widgets.TbButton', array(
            'label'=> 'Сохранить и добавить еще',
            'type'=>'primary',
            'buttonType'=>'submit',
            'icon'=>'plus white',
            'htmlOptions' => array(
                //'name' => 'add_more',
                'onclick' => '$("#add_more").val(1)'
            )
        )); ?>
    </div>

    <?php $this->endWidget(); ?>

</div>