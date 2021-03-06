<?php

/**
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the BSD 3-Clause License
 * that is bundled with this package in the file LICENSE.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/BSD-3-Clause
 *
 *
 * @author Malaysian Global Innovation & Creativity Centre Bhd <tech@mymagic.my>
 * @link https://github.com/mymagic/open_hub
 * @copyright 2017-2020 Malaysian Global Innovation & Creativity Centre Bhd and Contributors
 * @license https://opensource.org/licenses/BSD-3-Clause
 */

class SiteController extends Controller
{
	public $layout = 'frontend';

	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha' => array(
				'class' => 'CCaptchaAction',
				'backColor' => 0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page' => array(
				'class' => 'CViewAction',
			),
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
		//$this->render('index');
		$this->redirect(array('/cpanel'));
	}

	public function actionWelcome()
	{
		$this->render('welcome');
	}

	public function actionBooking()
	{
		$this->redirect(array('/mentor'));
	}

	public function actionAbout()
	{
		$this->activeMenuMain = 'about';
		$this->render('about');
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		$this->layout = '/layouts/error';

		if ($error = Yii::app()->errorHandler->error) {
			if (isset($error['type']) && $error['type'] == 'NoticeException') {
				$model = unserialize($error['message']);
				switch ($error['errorCode']) {
					case Notice_INFO:

						$this->render('pageNoticeInfo', $model);
						break;

					case Notice_SUCCESS:

						$this->render('pageNoticeSuccess', $model);
						break;

					case Notice_WARNING:

						$this->render('pageNoticeWarning', $model);
						break;

					default:

						$this->render('pageNoticeError', $model);
						break;
				}
			} elseif ($error['code'] == 403) {
				$model['title'] = 'Invalid Access';
				$model['message'] = !empty($error['message']) ? $error['message'] : $error;
				$this->render('pageAccessError', $model);
			} else {
				if (Yii::app()->request->isAjaxRequest) {
					echo $error['message'];
				} else {
					$model['title'] = 'Those do not believe in magic will never find it';
					$model['message'] = !empty($error['message']) ? $error['message'] : $error;
					$this->render('pageNoticeError', $model);
				}
			}
		}
	}

	/**
	 * Displays the contact page.
	 */
	public function actionContact()
	{
		$model['form'] = new ContactForm();
		if (isset($_POST['ContactForm'])) {
			$model['form']->attributes = $_POST['ContactForm'];
			if ($model['form']->validate()) {
				$name = '=?UTF-8?B?' . base64_encode($model['form']->name) . '?=';
				$subject = '=?UTF-8?B?' . base64_encode($model['form']->subject) . '?=';
				$headers = "From: $name <{$model['form']->email}>\r\n" .
					"Reply-To: {$model['form']->email}\r\n" .
					"MIME-Version: 1.0\r\n" .
					'Content-Type: text/plain; charset=UTF-8';

				mail(Yii::app()->params['adminEmail'], $subject, $model['form']->body, $headers);
				Yii::app()->user->setFlash('contact', Yii::t('app', 'Thank you for contacting us. We will respond to you as soon as possible.'));
				$this->refresh();
			}
		}
		$this->render('contact', array('model' => $model));
	}

	/**
	 * Displays the login page.
	 */
	public function actionLogin($returnUrl = '')
	{
		$this->redirect(array('//auth/login', 'returnUrl' => $returnUrl));
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout(true);
		$this->redirect(Yii::app()->params['baseUrl']);
	}

	public function actionSignup()
	{
		$this->redirect(array('signup'));
	}

	public function actionLostPassword()
	{
		throw new CHttpException(404, 'Page not found.');
	}

	public function actionResetLostPassword()
	{
		throw new CHttpException(404, 'Page not found.');
	}

	public function actionStampede()
	{
		$this->render('stampede');
	}

	public function actionTerminateAccount()
	{
		$this->render('accountTerminationInfo');
	}
}
