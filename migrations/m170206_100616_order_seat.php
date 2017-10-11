<?php
use yii\db\Migration;
use yii\db\Schema;

class m170206_100616_order_seat extends Migration {

	public function safeUp() {
		$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
		$this->createTable('{{%order_seat}}', [
				'id'       => Schema::TYPE_PK . '',
				'order_id' => Schema::TYPE_INTEGER . '(11) NOT NULL',
				'price_id' => Schema::TYPE_INTEGER . '(11) NOT NULL',
				'row'      => Schema::TYPE_STRING . '(4) NOT NULL',
				'number'   => Schema::TYPE_INTEGER . '(11) NOT NULL',
				'floor'    => Schema::TYPE_INTEGER . '(11) NOT NULL',
				'status'   => Schema::TYPE_BOOLEAN . '(1) NOT NULL DEFAULT "1"',
			], $tableOptions);
		$this->createIndex('order_id', '{{%order_seat}}', 'order_id', 0);
		$this->createIndex('price_id', '{{%order_seat}}', 'price_id', 0);
	}

	public function safeDown() {
		$this->dropIndex('order_id', '{{%order_seat}}');
		$this->dropIndex('price_id', '{{%order_seat}}');
		$this->dropTable('{{%order_seat}}');
	}
}
