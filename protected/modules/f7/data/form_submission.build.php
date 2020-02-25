<?php

return array(
	'layout' => '//layouts/backend',
	'isDeleteDisabled' => true,
	'moduleCode' => 'f7',
	'isAllowMeta' => true,
	'foreignRefer' => array('key' => 'id', 'title' => 'code'),
	'menuTemplate' => array(
		'index' => 'admin, create',
		'admin' => 'create',
		'create' => 'admin',
		'update' => 'admin, create, view',
		'view' => 'admin, create, update, delete',
	),
	'admin' => array(
		'list' => array('id', 'code', 'title', 'user_id'),
		'sortDefaultOrder' => 't.id DESC',
	),
	'structure' => array(
		'code' => array(
			'isUnique' => true,
			'isUUID' => true,
		),
		'status' => array(
			// define enum here, so generator can support database system that dont even supprot this data type such as sqlite
			'isEnum' => true, 'enumSelections' => array('draft' => 'Draft', 'submit' => 'Submit'),
		),
		'json_data' => array('isJson' => true),
	),
	// this foreignKey is mainly for crud view generation. model relationship will not use this at the moment
	'json' => array(
		'data' => array(
		),
	),
	'foreignKey' => array(
		'form_code' => array('relationName' => 'form', 'model' => 'Form', 'foreignReferAttribute' => 'title'),
		'user_id' => array('relationName' => 'user', 'model' => 'User', 'foreignReferAttribute' => 'username'),
	),
	/*
		eg: resource (this table), industry(target table), resource2industry(linked table)
		key: target table name, all small case singular form, eg 'industry'
		className: the model name of target table, CamelCase with uppercase first character, eg 'Industry'
		relationName: the MANY_MANY relations key generated by yii, all small case plural form of target table name, eg 'industries'
		relationTable: the linked table name, eg 'resource2industry'
		linkClassName: optional, if not provided, CamelCase with uppercase first character of relationTable, eg Resource2Industry
		label: optional
		notMasterData: optional boolean, default: false. If set true, means the target table can be huge so the input method will be different
	*/
	/*'many2many'=>array(

		'industry'=>array('className'=>'Industry', 'relationName'=>'industries', 'relationTable'=>'industry2intake'),

		'persona'=>array('className'=>'Persona', 'relationName'=>'personas', 'relationTable'=>'persona2intake'),

		'startup_stage'=>array('className'=>'StartupStage', 'relationName'=>'startupStages', 'relationTable'=>'startup_stage2intake'),

		'impact'=>array('className'=>'Impact', 'relationName'=>'impacts', 'relationTable'=>'impact2intake'),

	)*/
);
