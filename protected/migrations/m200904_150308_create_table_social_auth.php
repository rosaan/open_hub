<?php

class m200904_150308_create_table_social_auth extends CDbMigration
{
	public function up()
	{
		$this->createTable('social_auth', [
			'user_id' => 'integer NOT NULL',
			'provider_type' => 'string NOT NULL',
			'provider_token' => 'string NOT NULL',
			'date_added' => 'integer',
			'date_modified' => 'integer',
		]);
		$this->createIndex('token_social_auth', 'social_auth', 'provider_token', false);
		$this->createIndex('user_id_social_auth', 'social_auth', 'user_id', false);
		$this->addForeignKey('fk-social_auth-user', 'social_auth', 'user_id', 'user', 'id');
	}

	public function down()
	{
		$this->dropTable('forgot_password');
	}

	/*
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
	}

	public function safeDown()
	{
	}
	*/
}
