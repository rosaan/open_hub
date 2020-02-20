<?php
/* @var $this EventRegistrationController */
/* @var $model EventRegistration */

$this->breadcrumbs=array(
	Yii::t('backend', 'Event Registrations')=>array('index'),
	Yii::t('backend', 'Manage'),
);

$this->menu=array(
	array('label'=>Yii::t('app','Create EventRegistration'), 'url'=>array('/eventRegistration/create')),
	array('label'=>Yii::t('app','Sync from Bizzabo'), 'url'=>array('/eventRegistration/syncFromBizzabo')),
	array('label'=>Yii::t('app','Bulk Insert'), 'url'=>array('/eventRegistration/bulkInsert')),
	array('label'=>Yii::t('app','Housekeeping'), 'url'=>array('/eventRegistration/housekeeping')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-form form').submit(function(){
	$('#event-registration-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1><?php echo Yii::t('backend', 'Manage Event Registrations'); ?></h1>


<div class="panel panel-default">
<div class="panel-heading">
	<h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion" href="#collapse-eventRegistrationSearch"><i class="fa fa-search"></i>&nbsp; Advanced Search</a></h4>
</div>
<div id="collapse-eventRegistrationSearch" class="panel-collapse collapse">
	<div class="panel-body search-form">
	<?php $this->renderPartial('_search',array(
		'model'=>$model,
	)); ?>
	</div>
</div>
</div>

<?php $this->widget('application.components.widgets.GridView', array(
	'id'=>'event-registration-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		// array('name'=>'id', 'cssClassExpression'=>'id', 'value'=>$data->id, 'headerHtmlOptions'=>array('class'=>'id')),
		'registration_code',
		array('name'=>'event_code', 'cssClassExpression'=>'foreignKey', 'value'=>'$data->event->title', 'headerHtmlOptions'=>array('class'=>'foreignKey'), 'filter'=>Event::model()->getForeignReferList(false, true)),
		'email',
		'full_name',
		array('name'=>'is_attended', 'cssClassExpression'=>'boolean', 'type'=>'raw', 'value'=>'Html::renderBoolean($data->is_attended)', 'headerHtmlOptions'=>array('class'=>'boolean'), 'filter'=>$model->getEnumBoolean()), 

		array(
			'class'=>'application.components.widgets.ButtonColumn',
			'buttons' => array('delete' => array('visible'=>false)),		),
	),
)); ?>