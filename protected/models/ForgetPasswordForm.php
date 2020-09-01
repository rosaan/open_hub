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

class ForgetPasswordForm extends CFormModel
{
	public $username;

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			// username and password are required
			array('username', 'required'),
			array('username', 'email'),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'username' => Yii::t('core', 'Email'),
		);
	}

	public function reset()
	{
		Notice::debugFlash('ForgetPasswordForm.reset()');

		$model = User::username2obj($this->username);

		if (!$model) {
			Notice::debugFlash('Invalid username!');
		}

		$fullName = $model->profile->full_name;
		$randomKey = ysUtil::generateRandomKey(32, 32);
		$keyExpiration =  time() + (60 * 60 * 4);

		$_model = new ForgotPassword();
		$_model->user_id = $model->id;
		$_model->reset_password_key = $randomKey;
		$_model->date_expired = $keyExpiration;

		if ($_model->save(false)) {
			Yii::app()->mailer->compose(array(
				'to' => $model->username,
				'subject' => 'Hi ' . $fullName . ', request to reset password for ' . Yii::app()->name . ' account',
				'body' => 'auth/forgot',
				'items' => array('model' => array('name' => $fullName, 'key' => $randomKey)),
			));
			return true;
		}
		Notice::debugFlash('Reset password is not saved!');

		return true;
	}
}
