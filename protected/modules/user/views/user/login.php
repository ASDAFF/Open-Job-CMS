<?php
$this->pageTitle = Yii::app()->name . ' - ' . UserModule::t("Login");
$this->breadcrumbs = array(
    UserModule::t("Login"),
);

HView::echoAndSetTitle(UserModule::t("Login"));

if (isDemo()) {
    ?>

    <div class="row">

        <div class="span-6 text-info well well-small">
            <strong>Доступ для администратора:</strong><br>
            логин: admin<br>
            пароль: admin1<br>
        </div>

        <div class="span-6 text-info well well-small">
            <strong>Доступ для пользователя:</strong><br>
            логин: demo<br>
            пароль: demo<br>
        </div>

    </div>
    <br>

    <?php
}
?>

<?php if (Yii::app()->user->hasFlash('loginMessage')): ?>
    <div class="success">
        <?php echo Yii::app()->user->getFlash('loginMessage'); ?>
    </div>
<?php endif; ?>

    <p><?php echo UserModule::t("Please fill out the following form with your login credentials:"); ?></p>

    <div class="form">
        <?php echo CHtml::beginForm('', 'POST', array('class' => 'well')); ?>

        <p class="note"><?php echo UserModule::t('Fields with <span class="required">*</span> are required.'); ?></p>

        <?php echo CHtml::errorSummary($model); ?>

        <div class="row">
            <?php echo CHtml::activeLabelEx($model, 'username'); ?>
            <?php echo CHtml::activeTextField($model, 'username') ?>
        </div>

        <div class="row">
            <?php echo CHtml::activeLabelEx($model, 'password'); ?>
            <?php echo CHtml::activePasswordField($model, 'password') ?>
        </div>

        <div class="row">
            <p class="hint">
                <?php echo CHtml::link(UserModule::t("Register"), Yii::app()->getModule('user')->registrationUrl); ?>
                | <?php echo CHtml::link(UserModule::t("Lost Password?"), Yii::app()->getModule('user')->recoveryUrl); ?>
            </p>
        </div>

        <div class="row rememberMe">
            <?php echo CHtml::activeCheckBox($model, 'rememberMe'); ?>
            <?php echo CHtml::activeLabelEx($model, 'rememberMe', array('class' => 'inline')); ?>
        </div>

        <div class="row submit">
            <?php $this->widget('bootstrap.widgets.TbButton', array(
                'label' => UserModule::t("Login"),
                'type' => 'primary',
                'buttonType' => 'submit',
            )); ?>
        </div>

        <?php echo CHtml::endForm(); ?>
    </div><!-- form -->


<?php
$form = new CForm(array(
    'elements' => array(
        'username' => array(
            'type' => 'text',
            'maxlength' => 32,
        ),
        'password' => array(
            'type' => 'password',
            'maxlength' => 32,
        ),
        'rememberMe' => array(
            'type' => 'checkbox',
        )
    ),

    'buttons' => array(
        'login' => array(
            'type' => 'submit',
            'label' => 'Login',
        ),
    ),
), $model);
?>