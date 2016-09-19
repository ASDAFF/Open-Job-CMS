<?php $this->pageTitle=Yii::app()->name . ' - '.UserModule::t("Profile");
$this->breadcrumbs=array(
	UserModule::t("Profile"),
);

?><h1><?php echo UserModule::t('Your profile'); ?></h1>

<?php if(Yii::app()->user->hasFlash('profileMessage')): ?>
<div class="success">
	<?php echo Yii::app()->user->getFlash('profileMessage'); ?>
</div>
<?php endif; ?>



<?php

$attributes = array(
	array('name'=>'username', 'label'=>CHtml::encode($model->getAttributeLabel('username'))),
);

$profileFields=ProfileField::model()->forOwner()->sort()->findAll();
if ($profileFields) {
	foreach($profileFields as $field) {
		$attributes[] = array(
			'name' => $field->varname,
			'label' => CHtml::encode(UserModule::t($field->title)),
			'value' => (($field->widgetView($profile)) ? $field->widgetView($profile) : CHtml::encode((($field->range) ? Profile::range($field->range, $profile->getAttribute($field->varname)) : $profile->getAttribute($field->varname))))
		);
	}
}

$attributes[] = array('name' => 'email', 'label' => CHtml::encode($model->getAttributeLabel('email')));
$attributes[] = array('name' => 'create_at', 'label' => CHtml::encode($model->getAttributeLabel('create_at')));
$attributes[] = array('name' => 'lastvisit_at', 'label' => CHtml::encode($model->getAttributeLabel('lastvisit_at')));
$attributes[] = array('name' => 'countryName', 'label' => 'Cтрана');
$attributes[] = array('name' => 'regionName', 'label' => 'Регион');
$attributes[] = array('name' => 'cityName', 'label' => 'Город');
$attributes[] = array('name' => 'status', 'label' => CHtml::encode($model->getAttributeLabel('status')));

$this->widget('bootstrap.widgets.TbDetailView', array(
	'data'=>$model,
	'attributes'=>$attributes,
));

?>

