<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'install-form',
	'enableAjaxValidation'=>true,
)); ?>

	<p class="note">Поля отмеченные <span class="required">*</span> являются обязательными.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="install_box">
		<?php echo CHtml::activeCheckBox($model,'agreeLicense'); ?>
		<?php echo CHtml::activeLabel($model,'agreeLicense', array('style'=>'display:inline;')); ?>
		<?php echo $form->error($model,'agreeLicense'); ?>
	</div>

	<div class="pure-g">

		<div class="pure-u-12-24">
			<h3>Настройки базы данных</h3>
			<div class="row">
				<?php echo $form->labelEx($model,'dbUser'); ?>
				<?php echo $form->textField($model,'dbUser'); ?>
				<?php echo $form->error($model,'dbUser'); ?>
			</div>

			<div class="row">
				<?php echo $form->labelEx($model,'dbPass'); ?>
				<?php echo $form->textField($model,'dbPass'); ?>
				<?php echo $form->error($model,'dbPass'); ?>
			</div>

			<div class="row">
				<?php echo $form->labelEx($model,'dbHost'); ?>
				<?php echo $form->textField($model,'dbHost'); ?>
				<?php echo $form->error($model,'dbHost'); ?>
			</div>

			<div class="row">
				<?php echo $form->labelEx($model,'dbPort'); ?>
				<?php echo $form->textField($model,'dbPort'); ?>
				<?php echo $form->error($model,'dbPort'); ?>
			</div>

			<div class="row">
				<?php echo $form->labelEx($model,'dbName'); ?>
				<?php echo $form->textField($model,'dbName'); ?>
				<?php echo $form->error($model,'dbName'); ?>
			</div>

			<div class="row">
				<?php echo $form->labelEx($model,'dbPrefix'); ?>
				<?php echo $form->textField($model,'dbPrefix'); ?>
				<?php echo $form->error($model,'dbPrefix'); ?>
			</div>
		</div>

		<div class="pure-u-12-24">
			<h3>Данные администратора</h3>

			<div class="row">
				<?php echo $form->labelEx($model,'adminEmail'); ?>
				<?php echo $form->textField($model,'adminEmail'); ?>
				<?php echo $form->error($model,'adminEmail'); ?>
			</div>

			<div class="row">
				<?php echo $form->labelEx($model,'adminLogin'); ?>
				<?php echo $form->textField($model,'adminLogin'); ?>
				<?php echo $form->error($model,'adminLogin'); ?>
			</div>

			<div class="row">
				<?php echo $form->labelEx($model,'adminPass'); ?>
				<?php echo $form->textField($model,'adminPass'); ?>
				<?php echo $form->error($model,'adminPass'); ?>
			</div>
		</div>

	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Установить', array('class' => 'pure-button pure-button-primary')); ?>
	</div>

<?php $this->endWidget(); ?>
</div>

<div id="popup1" class="overlay">
	<div class="popup">
		<h2>Лицензионное соглашение</h2>
		<a class="close" href="#">&times;</a>
		<div class="content">
			<?php require 'license.php';?>
		</div>
	</div>
</div>