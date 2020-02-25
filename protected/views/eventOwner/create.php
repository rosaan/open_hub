<?php
/* @var $this EventOwnerController */
/* @var $model EventOwner */

$this->breadcrumbs = array(
	'Event Owners' => array('index'),
	Yii::t('backend', 'Create'),
);

$this->menu = array(
	array('label' => Yii::t('app', 'Manage EventOwner'), 'url' => array('/eventOwner/admin')),
);
?>

<h1><?php echo Yii::t('backend', 'Create Event Owner'); ?></h1>

<?php $this->renderPartial('_form', array('model' => $model)); ?>