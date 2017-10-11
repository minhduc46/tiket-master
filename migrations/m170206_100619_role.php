<?php
use yii\db\Migration;
use yii\db\Schema;

class m170206_100619_role extends Migration {

	public function safeUp() {
		$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
		$this->createTable('{{%role}}', [
				'id'               => Schema::TYPE_PK . '',
				'name'             => Schema::TYPE_STRING . '(255) NOT NULL',
				'permissions'      => Schema::TYPE_TEXT . ' NOT NULL',
				'is_backend_login' => Schema::TYPE_SMALLINT . '(1) NOT NULL DEFAULT "0"',
			], $tableOptions);
	}

	public function safeDown() {
		$this->dropTable('{{%role}}');
	}
}
