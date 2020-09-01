<?php
$this->breadcrumbs = array(
	Yii::t('app', 'Backend') => array('index'),
	Yii::t('app', 'Change Password'),
);
$this->menu = array(
	array('label' => Yii::t('app', 'My Account'), 'url' => array('/backend/me')),
	array('label' => Yii::t('app', 'Update Account'), 'url' => array('/backend/updateAccount'), 'linkOptions' => array('target' => '_blank')),
	array('label' => Yii::t('app', 'Change Password'), 'url' => array('/backend/changePassword'))
);
?>

<h1><?php echo Yii::t('app', 'Change Password') ?></h1>

<div class="">
	<?php $form = $this->beginWidget('ActiveForm', array(
		'id' => 'profile-form',
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

	<div class="form-group <?php echo $model->hasErrors('opassword') ? 'has-error' : '' ?>">
		<?php echo $form->bsLabelEx2($model, 'opassword'); ?>
		<div class="col-sm-10">
			<?php echo $form->bsPasswordField($model, 'opassword', array('size' => 30, 'maxlength' => 32)); ?>
			<?php echo $form->bsError($model, 'opassword'); ?>
		</div>
	</div>

	<div class="form-group <?php echo $model->hasErrors('npassword') ? 'has-error' : '' ?>">
		<?php echo $form->bsLabelEx2($model, 'npassword'); ?>
		<div class="col-sm-10">
			<?php echo $form->bsPasswordField($model, 'npassword', array('size' => 30, 'maxlength' => 32)); ?>
			<?php echo $form->bsError($model, 'npassword'); ?>
		</div>
	</div>

	<div class="form-group <?php echo $model->hasErrors('cpassword') ? 'has-error' : '' ?>">
		<?php echo $form->bsLabelEx2($model, 'cpassword'); ?>
		<div class="col-sm-10">
			<?php echo $form->bsPasswordField($model, 'cpassword', array('size' => 30, 'maxlength' => 32)); ?>
			<?php echo $form->bsError($model, 'cpassword'); ?>
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
			<?php echo $form->bsBtnSubmit(Yii::t('app', 'Change')); ?>
		</div>
	</div>

	<?php $this->endWidget(); ?>

</div>