<?php
use yii\db\Migration;
use yii\db\Schema;

class m170206_100613_event extends Migration {

	public function safeUp() {
		$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
		$this->createTable('{{%event}}', [
				'id'          => Schema::TYPE_PK . '',
				'name'        => Schema::TYPE_STRING . '(255) NOT NULL',
				'create_date' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP',
				'start_date'  => Schema::TYPE_DATE . ' NOT NULL',
				'end_date'    => Schema::TYPE_DATE . ' NOT NULL',
				'status'      => Schema::TYPE_BOOLEAN . '(1) NOT NULL DEFAULT "0"',
			], $tableOptions);
	}

	public function safeDown() {
		$this->dropTable('{{%event}}');
	}
}
