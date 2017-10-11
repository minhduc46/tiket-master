<?php
use yii\db\Migration;
use yii\db\Schema;

class m170206_100615_order extends Migration {

	public function safeUp() {
		$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
		$this->createTable('{{%order}}', [
				'id'               => Schema::TYPE_PK . '',
				'number'           => Schema::TYPE_INTEGER . '(11) NOT NULL',
				'event_id'         => Schema::TYPE_INTEGER . '(11) NOT NULL',
				'user_id'          => Schema::TYPE_INTEGER . '(11) NOT NULL',
				'agency_id'        => Schema::TYPE_INTEGER . '(11)',
				'customer_name'    => Schema::TYPE_STRING . '(255) NOT NULL',
				'customer_phone'   => Schema::TYPE_STRING . '(255) NOT NULL',
				'customer_address' => Schema::TYPE_STRING . '(255) NOT NULL',
				'discount'         => Schema::TYPE_DOUBLE . ' NOT NULL DEFAULT "0"',
				'discount_type'    => Schema::TYPE_BOOLEAN . '(1) NOT NULL DEFAULT "0"',
				'total'            => Schema::TYPE_INTEGER . '(11) NOT NULL',
				'remain'           => Schema::TYPE_INTEGER . '(11) NOT NULL DEFAULT "0"',
				'note'             => Schema::TYPE_TEXT . ' NOT NULL',
				'status'           => Schema::TYPE_BOOLEAN . '(1) NOT NULL DEFAULT "1"',
				'updated_date'     => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT "0000-00-00 00:00:00"',
				'booked_date'      => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP',
			], $tableOptions);
		$this->createIndex('agency_id', '{{%order}}', 'agency_id', 0);
		$this->createIndex('event_id', '{{%order}}', 'event_id', 0);
		$this->createIndex('user_id', '{{%order}}', 'user_id', 0);
		$this->createIndex('agency_id_2', '{{%order}}', 'agency_id', 0);
	}

	public function safeDown() {
		$this->dropIndex('agency_id', '{{%order}}');
		$this->dropIndex('event_id', '{{%order}}');
		$this->dropIndex('user_id', '{{%order}}');
		$this->dropIndex('agency_id_2', '{{%order}}');
		$this->dropTable('{{%order}}');
	}
}
