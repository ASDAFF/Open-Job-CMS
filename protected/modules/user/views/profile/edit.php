<?php
/**
 * @var $form BUserActiveForm
 * @var $model User
 */

$this->breadcrumbs=array(
	UserModule::t("Profile")=>array('profile'),
	UserModule::t("Edit"),
);

HView::echoAndSetTitle(UserModule::t('Edit profile'));

?>

<?php if(Yii::app()->user->hasFlash('profileMessage')): ?>
<div class="success">
<?php echo Yii::app()->user->getFlash('profileMessage'); ?>
</div>
<?php endif; ?>
<div class="form">
<?php $form = $this->beginWidget('BUserActiveForm', array(
	'id'=>'profile-form',
	'enableAjaxValidation'=>true,
	'htmlOptions' => array(
		'enctype'=>'multipart/form-data',
		'class'=>'well'
	),
)); ?>

	<p class="note"><?php echo UserModule::t('Fields with <span class="required">*</span> are required.'); ?></p>

	<?php echo $form->errorSummary(array($model,$profile)); ?>

    <div class="profile-ava">
        <?php
        echo $model->renderAva();

        $this->widget('ext.EAjaxUpload.EAjaxUpload',
            array(
                'id'=>'uploadFile',
                'config'=>array(
                    'action'=>Yii::app()->createUrl('/user/profile/upload'),
                    'allowedExtensions'=>array("jpg","jpeg","gif", "png"),//array("jpg","jpeg","gif","exe","mov" and etc...
                    'sizeLimit'=>1*1024*1024,// maximum file size in bytes
                    'minSizeLimit'=>1024,// minimum file size in bytes
                    'onComplete'=>"js:function(id, fileName, responseJSON){ profile.showAva(responseJSON); }",
                    'multiple'=>false,
                    'showMessage'=>"js:function(message){ notify.error(message); }"
                )
            ));
        ?>
    </div>

    <div class="clear"></div>

    <div class="row">
        <?php echo $form->labelEx($model,'type'); ?>
        <?php echo $form->dropDownList($model,'type', User::getListTypes()); ?>
        <?php echo $form->error($model,'type'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'activity'); ?>
        <?php echo $form->dropDownList($model,'activity', User::getActivityList()); ?>
        <?php echo $form->error($model,'activity'); ?>
    </div>

<?php
		$profileFields=$profile->getFields();
		if ($profileFields) {
			foreach($profileFields as $field) {
			?>
	<div class="row">
		<?php echo $form->labelEx($profile,$field->varname);

		if ($widgetEdit = $field->widgetEdit($profile)) {
			echo $widgetEdit;
		} elseif ($field->range) {
			echo $form->dropDownList($profile,$field->varname,Profile::range($field->range));
        } elseif ($field->field_type=="TEXT") {
            //echo $form->textArea($profile,$field->varname,array('rows'=>6, 'cols'=>50));
            $this->widget('BaseEditor', array(
                'model' => $profile,
                'attribute' => $field->varname,
            ));
		} else {
			echo $form->textField($profile,$field->varname,array('size'=>60,'maxlength'=>(($field->field_size)?$field->field_size:255)));
		}
		echo $form->error($profile,$field->varname); ?>
	</div>
			<?php
			}
		}
?>

	<div class="row">
		<?php echo $form->labelEx($model,'username'); ?>
		<?php echo $form->textField($model,'username',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'username'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'email'); ?>
		<?php echo $form->textField($model,'email',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($model,'email'); ?>
	</div>

    <?php if($model->type == User::TYPE_PROGRAMMER){ ?>

    <div class="row">
        <?php echo $form->labelEx($model,'salary_per_hour'); ?>
        <?php echo $form->textField($model,'salary_per_hour',array('size'=>10,'maxlength'=>10)) . ' ' . Project::getCurrencyName() . '/час'; ?>
        <?php echo $form->error($model,'salary_per_hour'); ?>
    </div>

    <?php } ?>

    <?php $this->renderPartial('/user/_selectLocation', array('form' => $form, 'model' => $model)); ?>

    <hr>

    <h5>Получать уведомления на email:</h5>
    <?php
    echo $form->checkBoxRow($model, 'sbs_newMess');
    echo $form->checkBoxRow($model, 'sbs_newRequest');
    echo $form->checkBoxRow($model, 'sbs_newReview');
    ?>

    <br>

	<?php //$this->renderPartial('/user/_selectLocation', array('model' => $model, 'form' => $form)); ?>

    <div class="row buttons">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'label'=>$model->isNewRecord ? UserModule::t('Create') : UserModule::t('Save'),
			'type'=>'primary',
			'buttonType'=>'submit',
		)); ?>
	</div>

	<?php $this->endWidget(); ?>

</div><!-- form -->


<script>
    var profile = {
        showAva: function(data){
            if(data.success == true){
                $('#user-ava-<?php echo $model->id;?>').html(data.avaHtml);
            }
        }
    }
</script>