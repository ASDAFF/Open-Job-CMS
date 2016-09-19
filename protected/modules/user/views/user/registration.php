<?php
/**
 * @var $model RegistrationForm
 * @var $location HLocation
 * @var $form CActiveForm
 */

$this->pageTitle = Yii::app()->name . ' - ' . UserModule::t("Registration");
$this->breadcrumbs = array(
    UserModule::t("Registration"),
);

HView::echoAndSetTitle(UserModule::t("Registration"));
?>

<?php if (Yii::app()->user->hasFlash('registration')): ?>
    <div class="success">
        <?php echo Yii::app()->user->getFlash('registration'); ?>
    </div>
<?php else: ?>

    <div class="form">
        <?php $form = $this->beginWidget('BUserActiveForm', array(
            'id' => 'verticalForm',
            'htmlOptions' => array(
                'enctype' => 'multipart/form-data',
                'class' => 'well'
            ),
        )); ?>

        <p class="note"><?php echo UserModule::t('Fields with <span class="required">*</span> are required.'); ?></p>

        <?php echo $form->errorSummary(array($model, $profile)); ?>

        <div class="row">
            <?php echo $form->labelEx($model, 'type'); ?>
            <?php echo $form->dropDownList($model, 'type', User::getListTypes()); ?>
            <?php echo $form->error($model, 'type'); ?>
        </div>

        <div class="row">
            <?php echo $form->labelEx($model, 'username'); ?>
            <?php echo $form->textField($model, 'username'); ?>
            <?php echo $form->error($model, 'username'); ?>
        </div>

        <div class="row">
            <?php echo $form->labelEx($model, 'password'); ?>
            <?php echo $form->passwordField($model, 'password'); ?>
            <?php echo $form->error($model, 'password'); ?>
            <p class="hint">
                <?php echo UserModule::t("Minimal password length 4 symbols."); ?>
            </p>
        </div>

        <div class="row">
            <?php echo $form->labelEx($model, 'verifyPassword'); ?>
            <?php echo $form->passwordField($model, 'verifyPassword'); ?>
            <?php echo $form->error($model, 'verifyPassword'); ?>
        </div>

        <div class="row">
            <?php echo $form->labelEx($model, 'email'); ?>
            <?php echo $form->textField($model, 'email'); ?>
            <?php echo $form->error($model, 'email'); ?>
        </div>

        <?php $this->renderPartial('/user/_selectLocation', array('form' => $form, 'model' => $model, 'location' => $location)); ?>

        <?php
        $profileFields = $profile->getFields();
        if ($profileFields) {
            foreach ($profileFields as $field) {
                ?>
                <div class="row">
                    <?php echo $form->labelEx($profile, $field->varname); ?>
                    <?php
                    if ($widgetEdit = $field->widgetEdit($profile)) {
                        echo $widgetEdit;
                    } elseif ($field->range) {
                        echo $form->dropDownList($profile, $field->varname, Profile::range($field->range));
                    } elseif ($field->field_type == "TEXT") {
                        echo $form->textArea($profile, $field->varname, array('rows' => 6, 'cols' => 50));
                    } else {
                        echo $form->textField($profile, $field->varname, array('size' => 60, 'maxlength' => (($field->field_size) ? $field->field_size : 255)));
                    }
                    ?>
                    <?php echo $form->error($profile, $field->varname); ?>
                </div>
            <?php
            }
        }
        ?>
        <?php if (UserModule::doCaptcha('registration')): ?>
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
        <?php endif; ?>

        <div class="row submit">
            <?php $this->widget('bootstrap.widgets.TbButton', array(
                'label' => UserModule::t("Register"),
                'type' => 'primary',
                'buttonType' => 'submit',
            )); ?>
        </div>

        <?php $this->endWidget(); ?>
    </div><!-- form -->
<?php endif; ?>
