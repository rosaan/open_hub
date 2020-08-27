<?php

class AuthController extends Controller
{
	public $layout = 'frontend';

	public function actionIndex()
	{
		if (!Yii::app()->user->isGuest) {
			$this->redirect(Yii::app()->homeUrl);
		}

		$this->redirect('auth/login');
	}

	public function actionLogin($returnUrl = '')
	{
		if (!Yii::app()->user->isGuest) {
			$this->redirect(Yii::app()->homeUrl);
		}

		$model = new LoginForm();

		if (isset($_POST['LoginForm'])) {
			$model->attributes = $_POST['LoginForm'];
			if ($model->login()) {
				$this->redirect($returnUrl ? $returnUrl : Yii::app()->homeUrl);
			}
		}

		$this->render('login', array('model' => $model));
	}

	public function actionSignup()
	{
		if (!Yii::app()->user->isGuest) {
			$this->redirect(Yii::app()->homeUrl);
		}

		$model = new SignupForm();

		if (isset($_POST['SignupForm'])) {
			$model->attributes = $_POST['SignupForm'];
			if ($model->validate() && $model->signup()) {
				Notice::flash(
					'<b>A verification link has been sent to your email account.</b> Please click on the link that has just been sent to your email account to verify your email and continue the registration process.',
					Notice_SUCCESS
				);
				$this->redirect('auth/login');
			}
		}

		$this->render('signup', array('model' => $model));
	}

	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
}
