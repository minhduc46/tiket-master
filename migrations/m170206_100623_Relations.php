<?php
use yii\db\Migration;

class m170206_100623_Relations extends Migration {

	public function safeUp() {
		$this->addForeignKey('fk_event_user_event_id', '{{%event_user}}', 'event_id', 'event', 'id', 'CASCADE', 'CASCADE');
		$this->addForeignKey('fk_event_user_user_id', '{{%event_user}}', 'user_id', 'user', 'id', 'CASCADE', 'CASCADE');
		$this->addForeignKey('fk_order_event_id', '{{%order}}', 'event_id', 'event', 'id', 'CASCADE', 'CASCADE');
		$this->addForeignKey('fk_order_user_id', '{{%order}}', 'user_id', 'user', 'id', 'CASCADE', 'CASCADE');
		$this->addForeignKey('fk_order_agency_id', '{{%order}}', 'agency_id', 'agency', 'id', 'NULL', 'CASCADE');
		$this->addForeignKey('fk_order_seat_order_id', '{{%order_seat}}', 'order_id', 'order', 'id', 'CASCADE', 'CASCADE');
		$this->addForeignKey('fk_order_seat_price_id', '{{%order_seat}}', 'price_id', 'price', 'id', 'CASCADE', 'CASCADE');
		$this->addForeignKey('fk_price_event_id', '{{%price}}', 'event_id', 'event', 'id', 'CASCADE', 'CASCADE');
		$this->addForeignKey('fk_profile_user_id', '{{%profile}}', 'user_id', 'user', 'id', 'CASCADE', 'CASCADE');
		$this->addForeignKey('fk_social_account_user_id', '{{%social_account}}', 'user_id', 'user', 'id', 'CASCADE', 'CASCADE');
		$this->addForeignKey('fk_token_user_id', '{{%token}}', 'user_id', 'user', 'id', 'CASCADE', 'CASCADE');
		$this->addForeignKey('fk_user_role_id', '{{%user}}', 'role_id', 'role', 'id', 'CASCADE', 'CASCADE');
		$this->addForeignKey('fk_user_branch_id', '{{%user}}', 'branch_id', 'branch', 'id', 'CASCADE', 'CASCADE');
	}

	public function safeDown() {
		$this->dropForeignKey('fk_event_user_event_id', '{{%event_user}}');
		$this->dropForeignKey('fk_event_user_user_id', '{{%event_user}}');
		$this->dropForeignKey('fk_order_event_id', '{{%order}}');
		$this->dropForeignKey('fk_order_user_id', '{{%order}}');
		$this->dropForeignKey('fk_order_agency_id', '{{%order}}');
		$this->dropForeignKey('fk_order_seat_order_id', '{{%order_seat}}');
		$this->dropForeignKey('fk_order_seat_price_id', '{{%order_seat}}');
		$this->dropForeignKey('fk_price_event_id', '{{%price}}');
		$this->dropForeignKey('fk_profile_user_id', '{{%profile}}');
		$this->dropForeignKey('fk_social_account_user_id', '{{%social_account}}');
		$this->dropForeignKey('fk_token_user_id', '{{%token}}');
		$this->dropForeignKey('fk_user_role_id', '{{%user}}');
		$this->dropForeignKey('fk_user_branch_id', '{{%user}}');
	}
}
