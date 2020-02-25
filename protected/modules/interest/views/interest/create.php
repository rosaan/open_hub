<?php
/* @var $this InterestController */
/* @var $model Interest */

$this->breadcrumbs = array(
	'Interests' => array('index'),
	Yii::t('backend', 'Create'),
);

$this->menu = array(
	array('label' => Yii::t('app', 'List Interest'), 'url' => array('/interest/interest/index')),
	array('label' => Yii::t('app', 'Manage Interest'), 'url' => array('/interest/interest/admin')),
);
?>

<h1><?php echo Yii::t('backend', 'Create Interest'); ?></h1>

<?php $this->renderPartial('_form', array('model' => $model)); ?>