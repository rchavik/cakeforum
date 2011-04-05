<?php

/**
 * CakeForum
 *
 * Developed by Inservio (http://www.inservio.ba)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @link          http://cakeforum.inservio.ba
 * @package       cakeforum
 * @version       0.1
 * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
 */

class ForumCategory extends AppModel {

	public $hasMany = array(
	
		'ForumPost' => array(
			'foreignKey' => 'forum_category_id',
			'dependent' => true,
		),
		
		'ForumTopic' => array(
			'foreignKey' => 'forum_category_id',
			'dependent' => true,
		)
	);
	
	public function getName($id = null) {
		
		$forum = $this->find('first', array(
				'conditions' => array('ForumCategory.id' => $id),
				'fields' => array('name'),
				'recursive' => -1
			)
		);
		
		return $forum['ForumCategory']['name'];	
	}
	
	/**
	 * Update counter replies and views
	 * @param type (replies / views)
	 * @param prefix (+ / -)
	 * @param forum_id
	 * @param number of topics/views (default: 1)
	 */	
	
	public function updateCounter($type = null, $prefix = null, $forum_id = null, $num = 1) {
		
		$this->recursive = -1;
		$conditions = array($type => $type . $prefix . $num);
		$this->updateAll($conditions, array('ForumCategory.id' => $forum_id));
		
	}
	
	/**
	 * Update last topic data 
	 * @param (int) forum ID *required
	 * @param (array) data
	 */
	
	public function updateLastTopic($forum_id = null, $data = null) {
		
		if (empty($data)) {
			$this->ForumTopic->unbindModel(array('hasMany' => array('ForumPost')));
			$lt = $this->ForumTopic->find('first', array(
					'conditions' => array('ForumTopic.forum_category_id' => $forum_id),
					'fields' => array('ForumTopic.id', 'ForumTopic.subject', 'ForumTopic.created', 'User.id', 'User.username'),
					'order' => 'ForumTopic.created DESC',
					'recursive' => 1
				)
			);
			
			$data = array(
				'last_topic_id' => $lt['ForumTopic']['id'], 
				'last_topic_subject' => $lt['ForumTopic']['subject'],
				'last_topic_created' => $lt['ForumTopic']['created'],
				'last_topic_user_id' => $lt['User']['id'],
				'last_topic_username' => $lt['User']['username'],
			);
		}

		$this->id = $forum_id;
		$this->save($data, false);				
	}

}
?>