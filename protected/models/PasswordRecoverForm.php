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

class PasswordRecoverForm extends CFormModel
{
	public $password;
	public $passwordc;

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			array('password, passwordc', 'required'),
			array('passwordc', 'compare', 'compareAttribute' => 'password', 'operator' => '==', 'message' => 'Password does not match!'),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'password' => Yii::t('app', 'Password'),
			'passwordc' => Yii::t('app', 'Confirm Password'),
		);
	}


	public static function checkResetPasswordKey($key)
	{
		return ForgotPassword::model()->exists('reset_password_key = :key AND date_expired > :time', array('key' => $key, 'time' => time()));
	}

	public function recover($key)
	{
		Notice::debugFlash('PasswordRecoverForm.recover()');

		$model = ForgotPassword::model()->find('reset_password_key = :key AND date_expired > :time', array('key' => $key, 'time' => time()));

		if (empty($model)) {
			return false;
		}

		$user = User::model()->findByPk($model->user_id);
		$user->password = $this->password;
		$user->stat_reset_password_count++;
		$model->date_expired = null;

		if ($user->save(false) && $model->save(false)) {
			Notice::debugFlash('Password recover successfull!');
			return true;
		}

		Notice::debugFlash('Password is not saved!');

		return false;
	}
}
