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

	/**
	 * Logs in the user using the given username and password in the model.
	 * @return boolean whether login is successful
	 */
	public function reset()
	{
		Notice::debugFlash('ForgetPasswordForm.login()');
		if ($this->_identity === null) {
			$this->_identity = new UserIdentity($this->username, $this->password);
			$this->_identity->authenticate('default');
		}

		Notice::debugFlash(Yii::t('notice', 'login error code: {error}', ['error' => $this->_identity->errorCode]));

		// login decision
		if ($this->_identity->errorCode === UserIdentity::ERROR_NONE) {
			Notice::debugFlash('UserIdentity::ERROR_NONE');
			$duration = $this->rememberMe ? 3600 * 24 * 30 : 0; // 30 days
			Yii::app()->user->login($this->_identity, $duration);

			return true;
		} elseif ($this->_identity->errorCode === UserIdentity::ERROR_USERNAME_INVALID) {
			Notice::debugFlash('UserIdentity::ERROR_USERNAME_INVALID');
			$this->addError('username', Yii::t('notice', 'Incorrect username'));
			$this->addError('password', Yii::t('notice', 'Incorrect password.'));
		} elseif ($this->_identity->errorCode === UserIdentity::ERROR_ACCOUNT_BLOCKED) {
			Notice::debugFlash('UserIdentity::ERROR_ACCOUNT_BLOCKED');
			$this->addError('username', Yii::t('core', 'Your account has been disabled by the system admin.'));
		} else {
			Notice::debugFlash('UserIdentity::ERROR_PASSWORD_INVALID');
			$this->addError('username', Yii::t('notice', 'Incorrect username'));
			$this->addError('password', Yii::t('notice', 'Incorrect password.'));
		}

		return false;
	}
}
