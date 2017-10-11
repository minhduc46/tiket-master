<?php
use yii\db\Migration;
use yii\db\Schema;

class m170206_100617_price extends Migration {

	public function safeUp() {
		$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
		$this->createTable('{{%price}}', [
				'id'       => Schema::TYPE_PK . '',
				'event_id' => Schema::TYPE_INTEGER . '(11) NOT NULL',
				'class'    => Schema::TYPE_INTEGER . '(11) NOT NULL',
				'price'    => Schema::TYPE_DOUBLE . ' NOT NULL',
				'color'    => Schema::TYPE_STRING . '(255) NOT NULL',
			], $tableOptions);
		$this->createIndex('event_id', '{{%price}}', 'event_id', 0);
	}

	public function safeDown() {
		$this->dropIndex('event_id', '{{%price}}');
		$this->dropTable('{{%price}}');
	}
}
