<?php
use yii\db\Migration;
use yii\db\Schema;

class m170206_100611_agency extends Migration {

	public function safeUp() {
		$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
		$this->createTable('{{%agency}}', [
				'id'      => Schema::TYPE_PK . '',
				'name'    => Schema::TYPE_STRING . '(255) NOT NULL',
				'phone'   => Schema::TYPE_STRING . '(255) NOT NULL',
				'address' => Schema::TYPE_STRING . '(255) NOT NULL',
			], $tableOptions);
	}

	public function safeDown() {
		$this->dropTable('{{%agency}}');
	}
}
