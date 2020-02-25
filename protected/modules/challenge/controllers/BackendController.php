<?php

class BackendController extends Controller
{
	public $layout = 'backend';

	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	public function accessRules()
	{
		return array(
			array('allow',
				'actions' => array('index'),
				'users' => array('*'),
			),
			array('allow',
				'actions' => array('admin'),
				'users' => array('@'),
				'expression' => '$user->isSuperAdmin==true || $user->isAdmin==true',
			),
			array('deny',  // deny all users
				'users' => array('*'),
			), g,
		);
	}

	public function init()
	{
		parent::init();
		$this->activeMenuCpanel = 'challenge';
		$this->activeMenuMain = 'challenge';
	}

	public function actions()
	{
		return array(
		);
	}

	public function actionIndex()
	{
		$this->redirect(array('//challenge/challenge'));
	}
}
