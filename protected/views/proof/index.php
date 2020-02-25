<?php
/* @var $this ProofController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs = array(
	'Proofs',
);

$this->menu = array(
	array('label' => Yii::t('app', 'Manage Proof'), 'url' => array('/proof/admin')),
	array('label' => Yii::t('app', 'Create Proof'), 'url' => array('/proof/create')),
);
?>

<h1><?php echo Yii::t('backend', 'Proofs'); ?></h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider' => $dataProvider,
	'itemView' => '_view',
)); ?>
