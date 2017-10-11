<?php
use yii\db\Migration;
use yii\db\Schema;

class m170206_100622_user extends Migration {

	public function safeUp() {
		$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
		$this->createTable('{{%user}}', [
				'id'                => Schema::TYPE_PK . '',
				'role_id'           => Schema::TYPE_INTEGER . '(11) NOT NULL DEFAULT "1"',
				'branch_id'         => Schema::TYPE_INTEGER . '(11) NOT NULL',
				'username'          => Schema::TYPE_STRING . '(255) NOT NULL',
				'email'             => Schema::TYPE_STRING . '(255) NOT NULL',
				'password_hash'     => Schema::TYPE_STRING . '(60) NOT NULL',
				'auth_key'          => Schema::TYPE_STRING . '(32) NOT NULL',
				'confirmed_at'      => Schema::TYPE_INTEGER . '(11)',
				'unconfirmed_email' => Schema::TYPE_STRING . '(255)',
				'blocked_at'        => Schema::TYPE_INTEGER . '(11)',
				'registration_ip'   => Schema::TYPE_STRING . '(45)',
				'created_at'        => Schema::TYPE_INTEGER . '(11) NOT NULL',
				'updated_at'        => Schema::TYPE_INTEGER . '(11) NOT NULL',
				'flags'             => Schema::TYPE_INTEGER . '(11) NOT NULL DEFAULT "0"',
				'last_login_at'     => Schema::TYPE_INTEGER . '(11)',
			], $tableOptions);
		$this->createIndex('user_unique_username', '{{%user}}', 'username', 1);
		$this->createIndex('user_unique_email', '{{%user}}', 'email', 1);
		$this->createIndex('fk_user_role_id', '{{%user}}', 'role_id', 0);
		$this->createIndex('branch_id', '{{%user}}', 'branch_id', 0);
	}

	public function safeDown() {
		$this->dropIndex('user_unique_username', '{{%user}}');
		$this->dropIndex('user_unique_email', '{{%user}}');
		$this->dropIndex('fk_user_role_id', '{{%user}}');
		$this->dropIndex('branch_id', '{{%user}}');
		$this->dropTable('{{%user}}');
	}
}
