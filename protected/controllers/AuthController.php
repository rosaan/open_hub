<?php

use Mpdf\Tag\P;

class AuthController extends Controller
{
	public $layout = 'frontend';

	public function actionIndex()
	{
		if (!Yii::app()->user->isGuest) {
			$this->redirect(Yii::app()->homeUrl);
		}

		$this->redirect(array('//auth/login'));
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
				$this->redirect(array('//auth/login'));
			}
		}

		$this->render('signup', array('model' => $model));
	}

	public function actionForget()
	{
		$model = new ForgetPasswordForm();
		if (isset($_POST['ForgetPasswordForm'])) {
			$model->attributes = $_POST['ForgetPasswordForm'];
			if ($model->validate() && $model->reset()) {
				return Notice::page('We have e-mailed your password reset link!', Notice_SUCCESS, array(
					'urlLabel' => 'OK', 'url' => $this->createAbsoluteUrl('//auth/login'),
				));
			}
		}
		$this->render('forget', array('model' => $model));
	}

	public function actionResendVerificationEmail($email = '')
	{
		if ($email) {
			$user = User::model()->find('username = :email', array('email' => $email));
			$fullName = $user->member->full_name;
			if (!empty($user)) {
				Yii::app()->mailer->compose(array(
					'to' => $user->username,
					'subject' => 'Hi ' . $fullName . ', please verify your ' . Yii::app()->name . ' account',
					'body' => 'auth/verify',
					'items' => array('model' => array('name' => $fullName, 'key' => $user->reset_password_key)),
				));
			}
		}
		Notice::flash(
			'<b>A verification link has been sent to your email account.</b> Please click on the link that has just been sent to your email account to verify your email and continue the registration process.',
			Notice_SUCCESS
		);
		$this->redirect(array('//auth/login'));
	}

	public function actionVerify($code = '')
	{
		if (!$code) {
			return Notice::page('Invalid request!', Notice_ERROR, array(
				'urlLabel' => 'Login', 'url' => $this->createAbsoluteUrl('//auth/login'),
			));
		}

		$user = User::model()->find('reset_password_key = :key', array('key' => $code));

		if (!$user || $user->date_activated) {
			return Notice::page('Invalid request!', Notice_ERROR, array(
				'urlLabel' => 'Login', 'url' => $this->createAbsoluteUrl('//auth/login'),
			));
		}

		$user->is_active = 1;
		$user->date_activated = time();

		if ($user->save()) {
			return Notice::page('Email successfully verified!', Notice_SUCCESS, array(
				'urlLabel' => 'Login', 'url' => $this->createAbsoluteUrl('//auth/login'),
			));
		}

		return Notice::page('Invalid request!', Notice_ERROR, array(
			'urlLabel' => 'Login', 'url' => $this->createAbsoluteUrl('//auth/login'),
		));
	}

	public function actionRecover($rpk = '')
	{
		// rpk === reset password key
		$model = new PasswordRecoverForm();

		if (!$rpk || !$model->checkResetPasswordKey($rpk)) {
			return Notice::page('Invalid request!', Notice_ERROR, array(
				'urlLabel' => 'Login', 'url' => $this->createAbsoluteUrl('//auth/login'),
			));
		}

		if (isset($_POST['PasswordRecoverForm'])) {
			$model->attributes = $_POST['PasswordRecoverForm'];

			if ($model->validate() && $model->recover($rpk)) {
				return Notice::page('Password changed successfully!', Notice_SUCCESS, array(
					'urlLabel' => 'Login', 'url' => $this->createAbsoluteUrl('//auth/login'),
				));
			}

			Notice::flash('Something went wrong! Please try again.', Notice_ERROR);
		}

		$this->render('recover', array('model' => $model));
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
