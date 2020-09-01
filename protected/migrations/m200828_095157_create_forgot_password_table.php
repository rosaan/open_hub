<?php

class m200828_095157_create_forgot_password_table extends CDbMigration
{
	public function up()
	{
		$this->createTable('forgot_password', [
			'id' => 'pk',
			'user_id' => 'integer NOT NULL',
			'reset_password_key' => 'string NOT NULL',
			'date_expired' => 'string DEFAULT NULL',
			'date_added' => 'integer',
			'date_modified' => 'integer',
		]);
		$this->addForeignKey('fk-forgot_password-user', 'forgot_password', 'user_id', 'user', 'id');
	}

	public function down()
	{
		$this->dropTable('forgot_password');
	}
}
