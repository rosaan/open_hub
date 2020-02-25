<?php
/* @var $this OrganizationController */
/* @var $model Organization */

$this->breadcrumbs = array(
	Yii::t('backend', 'Organizations') => array('index'),
	Yii::t('backend', 'Manage'),
);

$this->menu = YeeModule::composeNavItems('organizationAdminSideNav', Yii::app()->controller, array(
	array('label' => Yii::t('app', 'Create Organization'), 'url' => array('/organization/create')),
	array('label' => Yii::t('app', 'Merge Organizations'), 'url' => array('/organization/merge'), 'visible' => Yii::app()->user->getState('isAdmin')),
	array('label' => Yii::t('app', 'Housekeeping') . ' <span class="label label-warning">dev</span>', 'url' => array('/organization/housekeeping'), 'visible' => Yii::app()->user->getState('isDeveloper')),
));

Yii::app()->clientScript->registerScript('search', "
$('.search-form form').submit(function(){
	$('#organization-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1><?php echo $this->pageTitle ?></h1>

<div class="panel panel-default">
<div class="panel-heading">
	<h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion" href="#collapse-organizationSearch"><i class="fa fa-search"></i>&nbsp; Advanced Search</a></h4>
</div>
<div id="collapse-organizationSearch" class="panel-collapse collapse">
	<div class="panel-body search-form">
	<?php $this->renderPartial('_search', array(
		'model' => $model,
	)); ?>
	</div>
</div>
</div>

<?php $this->widget('application.components.widgets.GridView', array(
	'id' => 'organization-grid',
	'dataProvider' => $model->search(),
	'filter' => $model,
	'columns' => array(
		//array('name'=>'id', 'cssClassExpression'=>'id', 'value'=>$data->id, 'headerHtmlOptions'=>array('class'=>'id')),
		array('name' => 'image_logo', 'type' => 'raw', 'value' => 'Html::activeThumb($data, "image_logo", array("width"=>32))', 'filter' => false, 'htmlOptions' => array('class' => 'text-center')),
		'title',
		array('name' => 'is_active', 'cssClassExpression' => 'boolean', 'type' => 'raw', 'value' => 'Html::renderBoolean($data->is_active)', 'headerHtmlOptions' => array('class' => 'boolean'), 'filter' => $model->getEnumBoolean()),
		array('name' => 'date_added', 'cssClassExpression' => 'date', 'value' => 'Html::formatDateTime($data->date_added, \'medium\', false)', 'headerHtmlOptions' => array('class' => 'date'), 'filter' => false),

		array(
			'class' => 'application.components.widgets.ButtonColumn',
			'buttons' => array('delete' => array('visible' => false)),		),
	),
)); ?>