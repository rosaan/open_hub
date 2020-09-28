<?php

class SocialAuth extends SocialAuthBase
{
	public static function model($class = __CLASS__)
	{
		return parent::model($class);
	}

	public function init()
	{
		// custom code here
		// ...

		parent::init();

		// return void
	}

	public function beforeValidate()
	{
		// custom code here
		// ...

		return parent::beforeValidate();
	}

	public function afterValidate()
	{
		// custom code here
		// ...

		return parent::afterValidate();
	}

	protected function beforeSave()
	{
		// custom code here
		// ...

		return parent::beforeSave();
	}

	protected function afterSave()
	{
		// custom code here
		// ...

		return parent::afterSave();
	}

	protected function beforeFind()
	{
		// custom code here
		// ...

		parent::beforeFind();

		// return void
	}

	protected function afterFind()
	{
		// custom code here
		// ...

		parent::afterFind();

		// return void
	}

	public function attributeLabels()
	{
		$return = parent::attributeLabels();

		// custom code here
		// $return['title'] = Yii::t('app', 'Custom Name');

		return $return;
	}

	public static function findByIdentifier($type, $identifier)
	{
		$model = SocialAuth::model()->find('provider_type = :type AND provider_token = :identifier', array('type' => $type, 'identifier' => $identifier));
		if ($model) {
			return $model->user;
		}
		return false;
	}

	public static function findOne($type, $identifier)
	{
		return SocialAuth::model()->find('provider_type = :type AND provider_token = :identifier', array('type' => $type, 'identifier' => $identifier));
	}
}
