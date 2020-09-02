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

class SignupForm extends CFormModel
{
	public $email;
	public $firstname;
	public $lastname;
	public $password;
	public $passwordc;

	public function init()
	{
		parent::init();
	}

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			array('email, password, passwordc, firstname, lastname', 'required'),
			// email has to be a valid email address and matched confirmed email
			array('email', 'emailIsUnique'),
			array('email', 'email'),
			array('password', 'passwordValidation'),
			array('passwordc', 'compare', 'compareAttribute' => 'password', 'operator' => '==', 'message' => 'Password does not match!'),
		);
	}

	/**
	 * Declares customized attribute labels.
	 * If not declared here, an attribute would have a label that is
	 * the same as its name with the first letter in upper case.
	 */
	public function attributeLabels()
	{
		return array(
			'email' => Yii::t('app', 'Email'),
			'firstname' => Yii::t('app', 'Firstname'),
			'lastname' => Yii::t('app', 'Lastname'),
			'password' => Yii::t('app', 'Password'),
			'passwordc' => Yii::t('app', 'Confirm Password'),
			//'nickname' => Yii::t('app','Nick Name'),
			//'toc' => Yii::t('app','Terms &amp; Conditions'),
			//'agreetoc' => Yii::t('app','I have read and agree to terms and conditions'),
		);
	}

	public function emailIsUnique($attribute, $params)
	{
		if (!User::isUniqueUsername($this->$attribute)) {
			$this->addError($attribute, Yii::t('app', 'This email has already been taken.'));
		}
	}

	public function passwordValidation($attribute, $params)
	{
		if (strlen($this->$attribute) <= '8') {
			$this->addError($attribute, Yii::t('app', 'Your Password Must Contain At Least 8 Characters!'));
		} elseif (!preg_match("#[0-9]+#", $this->$attribute)) {
			$this->addError($attribute, Yii::t('app', 'Your Password Must Contain At Least 1 Number!'));
		} elseif (!preg_match("#[A-Z]+#", $this->$attribute)) {
			$this->addError($attribute, Yii::t('app', 'Your Password Must Contain At Least 1 Capital Letter!'));
		} elseif (!preg_match("#[a-z]+#", $this->$attribute)) {
			$this->addError($attribute, Yii::t('app', 'Your Password Must Contain At Least 1 Lowercase Letter!'));
		}
	}

	public function signup()
	{
		Notice::debugFlash('SignupForm.signup()');

		try {
			HUB::createLocalMember($this->email, $this->firstname . ' ' . $this->lastname, $this->password);
		} catch (\Throwable $th) {
			Notice::debugFlash(Yii::t('notice', 'signup error code: {error}', ['error' => $th->getMessage()]));
			return false;
		}

		return true;
	}
}
