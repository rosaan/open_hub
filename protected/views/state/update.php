<?php
/* @var $this StateController */
/* @var $model State */

$this->breadcrumbs = array(
	'States' => array('index'),
	$model->title => array('view', 'id' => $model->id),
	Yii::t('backend', 'Update'),
);

$this->menu = array(
	array('label' => Yii::t('app', 'Manage State'), 'url' => array('/state/admin')),
	array('label' => Yii::t('app', 'Create State'), 'url' => array('/state/create')),
	array('label' => Yii::t('app', 'View State'), 'url' => array('/state/view', 'id' => $model->id)),
);
?>

<h1><?php echo Yii::t('backend', 'Update State'); ?></h1>

<?php $this->renderPartial('_form', array('model' => $model)); ?>