<?php
/* @var $this AdminController */
/* @var $model Admin */
/* @var $form CActiveForm */
?>

<div class="">

	<?php $form = $this->beginWidget('ActiveForm', array(
		'id' => 'admin-form',
		// Please note: When you enable ajax validation, make sure the corresponding
		// controller action is handling ajax validation correctly.
		// There is a call to performAjaxValidation() commented in generated controller code.
		// See class documentation of CActiveForm for details on this.
		'enableAjaxValidation' => false,
		'htmlOptions' => array(
			'class' => 'form-horizontal crud-form',
			'role' => 'form',
			'enctype' => 'multipart/form-data',
		)
	)); ?>

	<?php echo Notice::inline(Yii::t('notice', 'Fields with <span class="required">*</span> are required.')); ?>
	<?php if ($model->hasErrors()) : ?>
		<?php echo $form->bsErrorSummary($model); ?>
	<?php endif; ?>


	<div class="form-group <?php echo $model->hasErrors('username') ? 'has-error' : '' ?>">
		<?php echo $form->bsLabelEx3($model, 'username'); ?>
		<div class="col-sm-9">
			<?php echo $form->bsEmailTextField($model, 'username', array('placeholder' => Yii::t('backend', "User's primary email address"))); ?>
			<?php echo $form->bsError($model, 'username'); ?>
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
			<?php echo $form->bsBtnSubmit($model->isNewRecord ? Yii::t('core', 'Create') : Yii::t('core', 'Save')); ?>
		</div>
	</div>

	<?php $this->endWidget(); ?>

</div><!-- form -->