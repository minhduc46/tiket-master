<?php
use yii\db\Migration;
use yii\db\Schema;

class m170206_100612_branch extends Migration {

	public function safeUp() {
		$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
		$this->createTable('{{%branch}}', [
				'id'    => Schema::TYPE_PK . '',
				'name'  => Schema::TYPE_STRING . '(255) NOT NULL',
				'phone' => Schema::TYPE_STRING . '(255) NOT NULL',
			], $tableOptions);
	}

	public function safeDown() {
		$this->dropTable('{{%branch}}');
	}
}
