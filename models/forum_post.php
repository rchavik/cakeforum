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

class ForumPost extends AppModel {

	public $name = 'ForumPost';
	
	public $validate = array(
      		'text' => array(
    			'rule' => 'notempty'
    		), 	
	);	

	public $belongsTo = array(
			
		'ForumTopic' => array(
			'foreignKey' => 'forum_topic_id'
		),
				
		'User' => array(
			'foreignKey' => 'user_id',
			'fields' => 'id, username'
		),
		
		'ForumCategory' => array(
			'foreignKey' => 'forum_category_id'
		)	
			
	);
	
	/**
	 * Callbacks
	 */
	
	public function beforeSave() {	
		if(!empty($this->data['ForumPost']['subject'])) {
			$this->data['ForumPost']['subject'] = strip_tags($this->data['ForumPost']['subject']);
		}	
		$this->data['ForumPost']['text'] = nl2br(strip_tags($this->data['ForumPost']['text']));
		return true;
	}
	
	/**
	 * Delete post
	 * @param post_id
	 * @param topic_id
	 * @param forum_id
	 * @return bool
	 */
	
	public function deletePost($post_id = null, $topic_id = null, $forum_id = null) {
		
		if ($this->delete($post_id)) {
			
			// update last post
			
			$this->ForumTopic->updateLastPost($topic_id);
			
			// update forum counters
			
			$this->ForumTopic->updateCounter('replies', '-', $topic_id);
			$this->ForumCategory->updateCounter('posts', '-', $forum_id);
			
			return true;
		}
		return false;
	}

}
?>