<?php

/**
 * NOTICE OF LICENSE.
 *
 * This source file is subject to the BSD 3-Clause License
 * that is bundled with this package in the file LICENSE.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/BSD-3-Clause
 *
 *
 * @author Malaysian Global Innovation & Creativity Centre Bhd <tech@mymagic.my>
 *
 * @see https://github.com/mymagic/open_hub
 *
 * @copyright 2017-2020 Malaysian Global Innovation & Creativity Centre Bhd and Contributors
 * @license https://opensource.org/licenses/BSD-3-Clause
 */
class AdminController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 *             using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout = 'backend';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 *
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array(
				'allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions' => array(
					'admin', 'create', 'createConnect', 'view',
					'block', 'blockConfirmed', 'unblock', 'unblockConfirmed',
					'resetPassword', 'resetPasswordConfirmed',
				),
				'users' => array('@'),
				// 'expression' => '$user->isSuperAdmin==true || $user->isAdminManager==true',
				'expression' => 'HUB::roleCheckerAction(Yii::app()->user->getState("rolesAssigned"), Yii::app()->controller)',
			),
			array(
				'allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions' => array('removeRole', 'removeRoleConfirmed', 'addRole', 'addRoleConfirmed'),
				'users' => array('@'),
				// 'expression' => '$user->isSuperAdmin==true || $user->isRoleManager==true',
				'expression' => 'HUB::roleCheckerAction(Yii::app()->user->getState("rolesAssigned"), Yii::app()->controller)',
			),
			array(
				'deny',  // deny all users
				'users' => array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 *
	 * @param int $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view', array(
			'model' => $this->loadModel($id),
		));
	}

	public function actionResetPassword($id)
	{
		$admin = $this->loadModel($id);
		if ($admin->user->hasRole('superAdmin') && !$this->user->hasRole('superAdmin')) {
			Notice::page(Yii::t('notice', 'You cannot reset password of an admin with super admin role, without having a super admin role by yourself'));
		}

		Notice::page(
			Yii::t('notice', "Are you sure to reset password for admin user '{username}'?", ['{username}' => $admin->user->username]),

			Notice_WARNING,
			array('url' => $this->createUrl('resetPasswordConfirmed', array('id' => $id)), 'cancelUrl' => $this->createUrl('view', array('id' => $id)))
		);
	}

	public function actionResetPasswordConfirmed($id)
	{
		$admin = $this->loadModel($id);

		// cannot change super admin's account if this admin manager do not have a super admin role as well
		if ($admin->user->hasRole('superAdmin') && !$this->user->hasRole('superAdmin')) {
			Notice::page(Yii::t('notice', 'You cannot reset password of an admin with super admin role, without having a super admin role by yourself'));
		}

		$newPassword = ysUtil::generateRandomPassword();
		$admin->user->password = $newPassword;
		$admin->user->stat_reset_password_count = $admin->user->stat_reset_password_count + 1;

		if ($admin->user->save()) {
			Notice::page(Yii::t('notice', "Password has been reset successfully. Admin user '{username}' new password is '{password}'.", ['{username}' => $admin->user->username, '{password}' => $newPassword]), Notice_WARNING, array('url' => $this->createUrl('view', array('id' => $id))));
		} else {
			Notice::flash(Yii::t('notice', "Failed to reset password for admin user '{username}' due to unknown reason.", ['{username}' => $admin->user->username]), Notice_ERROR);
			$this->redirect(array('admin/view', 'id' => $id));
		}
	}

	public function actionBlock($id)
	{
		$admin = $this->loadModel($id);
		if ($admin->user->hasRole('superAdmin') && !$this->user->hasRole('superAdmin')) {
			Notice::page(Yii::t('notice', 'You cannot block an admin with super admin role, without having a super admin role by yourself'));
		}

		if ($admin->user->is_active == 1) {
			Notice::page(
				Yii::t('notice', "Are you sure to block this admin user '{username}'? Blocked admin user will not beable to login.", ['{username}' => $admin->user->username]),
				Notice_WARNING,
				array('url' => $this->createUrl('blockConfirmed', array('id' => $id)), 'cancelUrl' => $this->createUrl('view', array('id' => $id)))
			);
		} else {
			Notice::flash(Yii::t('notice', "Admin user '{username}' is already blocked or inactive.", ['{username}' => $admin->user->username]), Notice_INFO);
			$this->redirect(array('admin/view', 'id' => $id));
		}
	}

	public function actionBlockConfirmed($id)
	{
		$admin = $this->loadModel($id);
		if ($admin->user->hasRole('superAdmin') && !$this->user->hasRole('superAdmin')) {
			Notice::page(Yii::t('notice', 'You cannot block an admin with super admin role, without having a super admin role by yourself'));
		}

		$admin->user->is_active = 0;

		if ($admin->user->save()) {
			Notice::flash(Yii::t('notice', "Admin user '{username}' is successfully blocked.", ['{username}' => $admin->user->username]), Notice_SUCCESS);
		} else {
			Notice::flash(Yii::t('notice', "Failed to block admin user '{username}' due to unknown reason.", ['{username}' => $admin->user->username]), Notice_ERROR);
		}

		$this->redirect(array('admin/view', 'id' => $id));
	}

	public function actionUnblock($id)
	{
		$admin = $this->loadModel($id);
		if ($admin->user->hasRole('superAdmin') && !$this->user->hasRole('superAdmin')) {
			Notice::page(Yii::t('notice', 'You cannot unblock an admin with super admin role, without having a super admin role by yourself'));
		}

		if ($admin->user->is_active == 0) {
			Notice::page(
				Yii::t('notice', "Are you sure to unblock this admin user '{username}'? Unblocked admin user is active and will beable to login again.", ['{username}' => $admin->user->username]),
				Notice_WARNING,
				array('url' => $this->createUrl('unblockConfirmed', array('id' => $id)), 'cancelUrl' => $this->createUrl('view', array('id' => $id)))
			);
		} else {
			Notice::flash(Yii::t('notice', "Admin user '{username}' is already unblocked or active.", ['{username}' => $admin->user->username]), Notice_INFO);
			$this->redirect(array('admin/view', 'id' => $id));
		}
	}

	public function actionUnblockConfirmed($id)
	{
		$admin = $this->loadModel($id);
		if ($admin->user->hasRole('superAdmin') && !$this->user->hasRole('superAdmin')) {
			Notice::page(Yii::t('notice', 'You cannot unblock an admin with super admin role, without having a super admin role by yourself'));
		}

		$admin->user->is_active = 1;

		if ($admin->user->save()) {
			Notice::flash(Yii::t('notice', "Admin user '{username}' is successfully unblocked.", ['{username}' => $admin->user->username]), Notice_SUCCESS);
		} else {
			Notice::flash(Yii::t('notice', "Failed to unblock admin user '{username}' due to unknown reason.", ['{username}' => $admin->user->username]), Notice_ERROR);
		}

		$this->redirect(array('admin/view', 'id' => $id));
	}

	public function actionRemoveRole($id, $roleCode)
	{
		$admin = $this->loadModel($id);
		if ($admin->user->hasRole('superAdmin') && !$this->user->hasRole('superAdmin')) {
			Notice::page(Yii::t('notice', 'You cannot remove role from an admin with super admin role, without having a super admin role by yourself'));
		}

		if ($admin->user->hasRole($roleCode)) {
			Notice::page(
				Yii::t('notice', "Are you sure to remove role '{role}' from admin user '{username}'? Admin user without a specific role will not beable to perform certain tasks.", ['{role}' => $roleCode, '{username}' => $admin->user->username]),
				Notice_WARNING,
				array('url' => $this->createUrl('removeRoleConfirmed', array('id' => $id, 'roleCode' => $roleCode)), 'cancelUrl' => $this->createUrl('view', array('id' => $id)))
			);
		} else {
			Notice::flash(Yii::t('notice', "User '{username}' has no role '{role}' to remove.", ['{username}' => $admin->user->username, '{role}' => $roleCode]), Notice_INFO);
			$this->redirect(array('admin/view', 'id' => $id));
		}
	}

	public function actionRemoveRoleConfirmed($id, $roleCode)
	{
		$admin = $this->loadModel($id);
		if ($admin->user->hasRole('superAdmin') && !$this->user->hasRole('superAdmin')) {
			Notice::page(Yii::t('notice', 'You cannot remove role from an admin with super admin role, without having a super admin role by yourself'));
		}

		if ($admin->user->removeRole($roleCode)) {
			Notice::flash(Yii::t('notice', "Role '{role}' is now removed from admin user '{username}' successfully. Changes will effect when the user relogin to the system.", ['{role}' => $roleCode, '{username}' => $admin->user->username]), Notice_SUCCESS);
		} else {
			Notice::flash(Yii::t('notice', "Failed to remove role '{role}' from admin user '{username}' due to unknown reason.", ['role' => $roleCode, '{username}' => $admin->user->username]), Notice_ERROR);
		}

		$this->redirect(array('admin/view', 'id' => $id));
	}

	public function actionAddRole($id, $roleCode)
	{
		$admin = $this->loadModel($id);
		if ($admin->user->hasNoRole($roleCode)) {
			Notice::page(
				Yii::t('notice', "Are you sure to add role '{role}' to admin user '{username}'? Admin user with a specific role will beable to perform certain tasks.", ['{role}' => $roleCode, '{username}' => $admin->user->username]),
				Notice_WARNING,
				array('url' => $this->createUrl('addRoleConfirmed', array('id' => $id, 'roleCode' => $roleCode)), 'cancelUrl' => $this->createUrl('view', array('id' => $id)))
			);
		} else {
			Notice::flash(Yii::t('notice', "User '{username}' already has role '{role}' assigned.", ['{username}' => $admin->user->username, '{role}' => $roleCode]), Notice_INFO);
			$this->redirect(array('admin/view', 'id' => $id));
		}
	}

	public function actionAddRoleConfirmed($id, $roleCode)
	{
		$admin = $this->loadModel($id);
		if ($admin->user->addRole($roleCode)) {
			Notice::flash(Yii::t('notice', "Role '{role}' is now added to admin user '{username}' successfully. Changes will effect when the user relogin to the system.", ['{role}' => $roleCode, '{username}' => $admin->user->username]), Notice_SUCCESS);
		} else {
			Notice::flash(Yii::t('notice', "Failed to add role '{role}' to admin user '{username}' due to unknown reason.", ['{role}' => $roleCode, '{username}' => $admin->user->username]), Notice_ERROR);
		}

		$this->redirect(array('admin/view', 'id' => $id));
	}

	public function actionCreate()
	{
		$model = new Admin('create');

		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($model);

		if (isset($_POST['Admin'])) {
			$model->attributes = $_POST['Admin'];
			$user = User::username2obj($_POST['Admin']['username']);
			if ($model->validate()) {
				$transaction = Yii::app()->db->beginTransaction();

				try {
					$admin = new Admin();
					$admin->user_id = $user->id;
					$admin->username = $user->username;
					$admin->full_name = $user->member->full_name;
					$admin->save();
					$transaction->commit();
				} catch (Exception $e) {
					Notice::debugFlash($e->getMessage());
					$transaction->rollBack();
				}
			}
		}

		$this->render('create', array(
			'model' => $model,
		));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$this->redirect(array('admin/admin'));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model = new Admin('search');
		$model->unsetAttributes();  // clear any default values
		if (isset($_GET['Admin'])) {
			$model->attributes = $_GET['Admin'];
		}
		if (Yii::app()->request->getParam('clearFilters')) {
			EButtonColumnWithClearFilters::clearFilters($this, $model);
		}

		$this->render('admin', array(
			'model' => $model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 *
	 * @param int $id the ID of the model to be loaded
	 *
	 * @return Admin the loaded model
	 *
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model = Admin::model()->findByPk($id);
		if ($model === null) {
			throw new CHttpException(404, 'The requested page does not exist.');
		}

		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 *
	 * @param Admin $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if (isset($_POST['ajax']) && $_POST['ajax'] === 'admin-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
