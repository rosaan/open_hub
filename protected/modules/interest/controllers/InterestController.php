<?php

class InterestController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/backend', meaning
	 * using two-column layout. See 'protected/views/layouts/backend.php'.
	 */
	public $layout = 'layouts.backend';

	public function actions()
	{
		return array();
	}

	public function init()
	{
		parent::init();
		$this->activeMenuMain = 'interest'; //active menu name based on NameModule.php getNavItems() active attribute
	}

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array(
				'allow',  // allow all users to perform 'index' and 'view' actions
				'actions' => array('index'),
				'users' => array('*'),
			),
			array(
				'allow', // allow authenticated user to perform 'create', 'update', 'admin' and 'delete' actions
				'actions' => array('create', 'update', 'setting'),
				'users' => array('@')
			),
			array(
				'allow', // allow authenticated user to perform 'create', 'update', 'admin' and 'delete' actions
				'actions' => array('list', 'view', 'create', 'update', 'admin', 'delete'),
				'users' => array('@'),
				'expression' => '$user->isSuperAdmin==true || $user->isAdmin==true',
			),
			array(
				'deny',  // deny all users
				'users' => array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view', array(
			'model' => $this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model = new Interest;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if (isset($_POST['Interest'])) {
			$model->attributes = $_POST['Interest'];

			if ($model->save()) {
				Yii::app()->esLog->log(sprintf("created interest for user '%s'", $model->user->username), 'interest', array('trigger' => 'InterestController::actionCreate', 'model' => 'Interest', 'action' => 'create', 'id' => $model->id));
				$this->redirect(array('view', 'id' => $model->id));
			}
		}

		$this->render('create', array(
			'model' => $model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model = $this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if (isset($_POST['Interest'])) {
			$model->attributes = $_POST['Interest'];

			if ($model->save()) {
				Yii::app()->esLog->log(sprintf("updated interest for user '%s'", $model->user->username), 'interest', array('trigger' => 'InterestController::actionUpdate', 'model' => 'Interest', 'action' => 'update', 'id' => $model->id));
				$this->redirect(array('view', 'id' => $model->id));
			}
		}

		$this->render('update', array(
			'model' => $model,
		));
	}

	public function actionSetting()
	{
		$model = Interest::model()->find('t.user_id=:userId', array(':userId' => Yii::app()->user->id));

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		$this->layout = 'layouts.cpanel'; //default layout for cpanel
		$this->layoutParams['bodyClass'] = str_replace('gray-bg', 'white-bg', $this->layoutParams['bodyClass']);
		$this->cpanelMenuInterface = 'cpanelNavSetting'; //cpanel menu interface type ex. cpanelNavDashboard, cpanelNavSetting, cpanelNavCompany, cpanelNavCompanyInformation
		$this->activeMenuCpanel = 'interest'; //active menu name based on NameModule.php getNavItems() active attribute

		if (isset($_POST['Interest'])) {
			$model->attributes = $_POST['Interest'];

			if ($model->save()) {
				Notice::flash(Yii::t('notice', 'Your interest is successfully updated.'), Notice_SUCCESS);
				$this->redirect('setting');
			}
		}

		$this->render('setting', array(
			'model' => $model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		Yii::app()->esLog->log(sprintf("delete interest for user '%s'", $this->loadModel($id)->user->username), 'interest', array('trigger' => 'InterestController::actionDelete', 'model' => 'Interest', 'action' => 'delete', 'id' => $id));

		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if (!isset($_GET['ajax'])) {
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		}
	}

	/**
	 * Index
	 */
	public function actionIndex()
	{
		$this->redirect(array('interest/admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionList()
	{
		$dataProvider = new CActiveDataProvider('Interest');
		$dataProvider->pagination->pageSize = 5;
		$dataProvider->pagination->pageVar = 'page';

		$this->render('index', array(
			'dataProvider' => $dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model = new Interest('search');
		$model->unsetAttributes();  // clear any default values
		if (isset($_GET['Interest'])) {
			$model->attributes = $_GET['Interest'];
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
	 * @param integer $id the ID of the model to be loaded
	 * @return Interest the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model = Interest::model()->findByPk($id);
		if ($model === null) {
			throw new CHttpException(404, 'The requested page does not exist.');
		}
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Interest $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if (isset($_POST['ajax']) && $_POST['ajax'] === 'interest-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
