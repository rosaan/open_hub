<?php
/* @var $this EventOwnerController */
/* @var $model EventOwner */

$this->breadcrumbs = array(
	Yii::t('backend', 'Event Owners') => array('index'),
	Yii::t('backend', 'Manage'),
);

$this->menu = array(
	array('label' => Yii::t('app', 'Create EventOwner'), 'url' => array('/eventOwner/create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-form form').submit(function(){
	$('#event-owner-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1><?php echo Yii::t('backend', 'Manage Event Owners'); ?></h1>


<div class="panel panel-default">
<div class="panel-heading">
	<h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion" href="#collapse-eventOwnerSearch"><i class="fa fa-search"></i>&nbsp; Advanced Search</a></h4>
</div>
<div id="collapse-eventOwnerSearch" class="panel-collapse collapse">
	<div class="panel-body search-form">
	<?php $this->renderPartial('_search', array(
		'model' => $model,
	)); ?>
	</div>
</div>
</div>

<?php $this->widget('application.components.widgets.GridView', array(
	'id' => 'event-owner-grid',
	'dataProvider' => $model->search(),
	'filter' => $model,
	'columns' => array(
		array('name' => 'id', 'cssClassExpression' => 'id', 'value' => $data->id, 'headerHtmlOptions' => array('class' => 'id')),
		array('name' => 'event_code', 'cssClassExpression' => 'foreignKey', 'value' => '$data->event->title', 'headerHtmlOptions' => array('class' => 'foreignKey'), 'filter' => Event::model()->getForeignReferList(false, true)),
		'department',

		array(
			'class' => 'application.components.widgets.ButtonColumn',
					),
	),
)); ?>
