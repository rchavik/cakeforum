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

class ForumTopic extends AppModel {

	public $name = 'ForumTopic';
	
	public $userID;
	
	public $validate = array(
    		'subject' => array(
				'lenght' => array('rule' => array('maxLength', 150)),
    			'required' => array('rule' => 'notempty')
    		),	
    		'text' => array(
    			'required' => array('rule' => 'notempty')
    		),	
	);

	public $belongsTo = array(
		'User' => array(
			'foreignKey' => 'user_id',
			'fields' => 'id, username'
		)
	);

	public $hasMany = array(
		'ForumPost' => array(
			'foreignKey' => 'forum_topic_id',
			'dependent' => true
		)
    );   
	
    /**
     * Callbacks
     */
	
	public function beforeSave() {		
		if(isset($this->data['ForumTopic']['subject']) && isset($this->data['ForumTopic']['text'])) {
			$this->data['ForumTopic']['subject'] = strip_tags($this->data['ForumTopic']['subject']);	
			$this->data['ForumTopic']['text'] = nl2br(strip_tags($this->data['ForumTopic']['text']));				
		}
		return true;
	}	
	
	/**
	 * Get topic title
	 * @param (int) topic ID
	 * @return (string) topic subject
	 */

	public function getName($topic_id = null) {
		
		$t = $this->find('first', array(
				'conditions' => array('ForumTopic.id' => $topic_id),
				'fields' => array('ForumTopic.subject'),
				'recursive' => -1
			)
		);
		
		return $t['ForumTopic']['subject'];		
	}
	
	/**
	 * Update counter replies and views
	 * @param type (replies / views)
	 * @param prefix (+ / -)
	 * @param topic_id
	 * @param number
	 */
	
	public function updateCounter($type = null, $prefix = null, $topic_id = null, $num = 1) {
		
		$this->recursive = -1;
		$conditions = array($type => $type . $prefix . $num);
		$this->updateAll($conditions, array('ForumTopic.id' => $topic_id));	
	}
	
	/**
	 * Search forum topic by subject
	 * @param string query
	 */
	
	public function search($q = null) {
		
		$conditions = "MATCH (subject) AGAINST ('$q')";						
		$fields = "ForumTopic.id, ForumTopic.forum_category_id, ForumTopic.subject, ForumTopic.created, ForumTopic.views, ForumTopic.replies, User.id, User.username";
		
		$topics = $this->find('all', array(
				'conditions' => $conditions,
				'fields' => $fields,
				'order' => array('ForumTopic.created' => 'DESC'),
				'recursive' => -1
			)
		);

		return $topics;		
	}
	
	/**
	 * Update last post
	 * @param (int) topic ID *required
	 * @param (array) data
	 */
	
	public function updateLastPost($topic_id = null, $data = null) {
		
		if (empty($data)) {
			
			App::import('Contoller', 'Forum');
			$forum = new ForumController();
			
			$this->ForumPost->unbindModel(array('belongsTo' => array('Topic', 'Forum')));
			
			$t = $this->ForumPost->find('first', array(
					'conditions' => array('ForumPost.forum_topic_id' => $topic_id),
					'order' => 'ForumPost.created DESC',
					'recursive' => 1
				)
			);
			$this->recursive = -1;
			$post = $this->read('replies', $topic_id);
	
			$data = array(
				'last_post_id' => $t['ForumPost']['id'],
				'last_post_page' => $forum->getLastPageNumber($topic_id,  $post),
				'last_post_created' => $t['ForumPost']['created'],
				'last_post_user_id' => $t['User']['id'],
				'last_post_username' => $t['User']['username'],
			);		

		}

		$this->id = $topic_id;
		$this->save($data, false);	
	}

}
?>