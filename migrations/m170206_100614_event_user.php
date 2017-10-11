<?php
use yii\db\Migration;
use yii\db\Schema;

class m170206_100614_event_user extends Migration {

	public function safeUp() {
		$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
		$this->createTable('{{%event_user}}', [
				'id'       => Schema::TYPE_PK . '',
				'event_id' => Schema::TYPE_INTEGER . '(11) NOT NULL',
				'user_id'  => Schema::TYPE_INTEGER . '(11) NOT NULL',
			], $tableOptions);
		$this->createIndex('event_id', '{{%event_user}}', 'event_id', 0);
		$this->createIndex('user_id', '{{%event_user}}', 'user_id', 0);
	}

	public function safeDown() {
		$this->dropIndex('event_id', '{{%event_user}}');
		$this->dropIndex('user_id', '{{%event_user}}');
		$this->dropTable('{{%event_user}}');
	}
}
