<?php
/**
 * Created by Navatech
 * @project ticket-ibgroup-vn
 * @author  Le Phuong
 * @email phuong17889@gmail.com
 * @time    2/6/2017 4:49 PM
 */
namespace app\components;

use yii\db\ActiveQuery;

class ActiveRecord extends \yii\db\ActiveRecord {

	/**
	 * @param string $class           Example: Author::className()
	 * @param string $related_table   Example: 'post_author'
	 * @param array  $link_to_related Example: ['post_id' => 'id']
	 * @param array  $link_related_to Example: ['id' => 'author_id']
	 *
	 * @return ActiveQuery
	 */
	public function hasManyToMany($class, $related_table, array $link_to_related, array $link_related_to) {
		return $this->hasMany($class, $link_related_to)->viaTable($related_table, $link_to_related);
	}
}