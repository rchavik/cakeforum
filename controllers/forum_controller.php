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

class ForumController extends AppController {
	
	public $uses = array('ForumCategory', 'ForumTopic', 'ForumPost');
	
	public $helpers = array('Time');
	
	// Number of posts per page
	private $numPosts = 5;
	
	// Number of topics per page
	private $numTopics = 10;
	
	
	
	public function beforeFilter() {	
		
		parent::beforeFilter();
		$this->Auth->allowedActions = array('index', 'view', 'topic');
		
	}
	
	/**
	 * Forum index, show forum categories
	 */
	
	public function index()  {
		
		$this->pageTitle = 'Index';
		
		$forums = $this->ForumCategory->find('all', array(
				'order' => 'order ASC',
				'recursive' => -1
			)
		);
		
		$this->set('categories', $forums);		
	}
	
	/**
	 * View forum
	 * @param (int) forum ID
	 */
	
	public function view($id = null) {
		
		$forumName = $this->ForumCategory->getName($id);
		
		$this->pageTitle = $forumName;
		
		$this->ForumTopic->userID = $this->userID;
	
		// get topics
		
		$this->ForumTopic->unbindModel(array('hasMany' => array('ForumPost')), false);

		$this->paginate['ForumTopic'] = array(
			'limit' => $this->numTopics, 
			'order' => 'ForumTopic.last_post_created DESC',
			'recursive' => 1
		);	
		
		$topics = $this->paginate('ForumTopic', array('ForumTopic.forum_category_id' => $id));	
		
		if (empty($topics)){
			$topics = null;
		}
				
		$this->set('topics', $topics);
		$this->set('forumId', $id);
		$this->set('forumName', $forumName);
		
	}
	
	/**
	 * View topic
	 * @param (int) topic ID
	 * @param (int) forum ID
	 */
		
	public function topic($topic_id = null, $forum_id = null) {
		
		// get forum name
		$forumName = $this->ForumCategory->getName($forum_id);
		
		// get topic
		$topic = $this->ForumTopic->find('first', array(
				'conditions' => array('ForumTopic.id' => $topic_id),
				'fields' => 'id, subject',
				'recursive' => -1
			)
		);
		
		// set page title
		$this->pageTitle = $forumName . ' » ' . $topic['ForumTopic']['subject'];
		
		// get posts
		
		$this->ForumPost->unbindModel(array('belongsTo' => array('ForumCategory', 'ForumTopic')), false);
	
		$this->paginate = array(
			'ForumTopic' => array('limit' => $this->numPosts),
			'ForumPost' => array('order' => array('ForumPost.id' => 'ASC'))
		);
		
		$posts = $this->paginate('ForumPost',  array('ForumPost.forum_topic_id' => $topic_id));
		
		$this->ForumTopic->updateCounter('views', '+', $topic_id);
		
		$this->set(array(
				'forumName' => $forumName,
				'topic' => $topic,
				'posts' => $posts,
				'topicId' => $topic_id,
				'forumId' => $forum_id
			)
		);		
	}
	
	/**
	 * Start new topic
	 * @param (int) forum ID
	 */
 	
	public function newtopic($forum_id = null) {
		
		// get forum name
		$forumName = $this->ForumCategory->getName($forum_id);	
		
		// set page title
		$this->pageTitle = 'New topic';
		
		if(!$forumName) {
			$this->redirect(array('controller' => 'forum', 'action' => 'index'));
		}
		
		if(!empty($this->data)) {
		
			$this->data['ForumTopic']['forum_category_id'] = $forum_id;
			$this->data['ForumTopic']['user_id'] = $this->userID;

			if($this->ForumTopic->save($this->data)) {		

				
				$postData = array('ForumPost' => 
					array(
						'forum_topic_id' => $this->ForumTopic->getLastInsertID(),
						'forum_category_id' => $forum_id,
						'user_id' => $this->userID,
						'text' => $this->data['ForumTopic']['text'],
						'topic' => '1'
					)
				);
				
				$topic_id = $this->ForumTopic->getLastInsertID();
				
				// save post			
					
				$this->ForumPost->save($postData);
				
				// update last topic data in forum category			
				
				$lastTopic = array(
					'last_topic_id' => $topic_id,
					'last_topic_subject' => strip_tags($this->data['ForumTopic']['subject']),
					'last_topic_created' => date('Y-m-d H:m:s'),				
					'last_topic_user_id' => $this->userID,
					'last_topic_username' => $this->Auth->user('username'),				
				);

				$this->ForumCategory->updateLastTopic($forum_id, $lastTopic);
				
				// save last post data
				
				$lastPost = array(
					'last_post_id' => $this->ForumPost->getInsertID(),
					'last_post_page' => 0,
					'last_post_created' => date('Y-m-d H:i:s'),
					'last_post_user_id' => $this->userID,
					'last_post_username' => $this->Auth->user('username')
				);					
				
				$this->ForumTopic->updateLastPost($topic_id, $lastPost);

				// update counters

				$this->ForumCategory->updateCounter('topics', '+', $forum_id);
				
				$this->redirect(array('controller' => 'forum', 'action' => 'topic', $topic_id, $forum_id));
			}
		}

		$this->set('forumName', $forumName);		
		$this->set('forumId', $forum_id);
		
	}
	
	/**
	 * Reply 
	 * @param (int) topic ID
	 * @param (int) forum ID
	 * @param (string) quote: 'quote'
	 * @param (int) post ID
	 */
		
	public function reply($topic_id = null, $forum_id = null) {
		
		$topicExist = $this->ForumTopic->hasAny(array('id' => $topic_id));
		
		if (!$topicExist) {
			$this->redirect(array('controller' => 'forum', 'action' => 'index'));
		}
		
		$this->pageTitle = 'Reply';
		
		if(!empty($this->data)) {
			
			$this->data['ForumPost']['forum_topic_id'] = $topic_id;
			$this->data['ForumPost']['forum_category_id'] = $forum_id;
			$this->data['ForumPost']['user_id'] = $this->userID;
			
			if($this->ForumPost->save($this->data)) {
				
				// update counters
				
				$this->ForumCategory->updateCounter('posts', '+', $forum_id);
				$this->ForumTopic->updateCounter('replies', '+', $topic_id);
				
				// get last page number
				
				$numPage = $this->getLastPageNumber($topic_id);
				
				// update last post
				
				$lastPost = array(
					'last_post_id' => $this->ForumPost->getInsertID(),
					'last_post_page' => $numPage,
					'last_post_created' => date('Y-m-d H:i:s'),
					'last_post_user_id' => $this->userID,
					'last_post_username' => $this->Auth->user('username')
				);							
				
				$this->ForumTopic->updateLastPost($topic_id, $lastPost);
						
				$this->redirect(array('controller' => 'forum', 'action' => 'topic', $topic_id, $forum_id, '/page:' . $numPage));
			}
		}
		
		$this->getForumPath($forum_id, $topic_id);
		$this->set('topicId', $topic_id);
		$this->set('forumId', $forum_id);
		
	}
	
	/**
	 * Edit post
	 * only owner of post
	 */
	
	public function editpost($post_id = null, $topic_id = null, $forum_id = null) {
		
		$post = $this->ForumPost->find('first', array(
				'conditions' => array('ForumPost.id' => $post_id, 'ForumPost.user_id' => $this->userID),
				'recursive' => -1
			)
		);	
		
		if (empty($post)) {
			$this->redirect(array('controller' => 'forum', 'action' => 'index'));	
		}
		
		if ($post['ForumPost']['topic']) {	
			$topic = $this->ForumTopic->find('first', array(
					'conditions' => array('ForumTopic.id' => $topic_id, 'ForumTopic.user_id' => $this->userID),
					'fields' => 'id, subject',
					'recursive' => -1
				)
			);				
		}			
		
		if (empty($this->data)) {
			
			$this->data = $post;
			$this->data['ForumPost']['text'] = $this->br2nl($this->data['ForumPost']['text']);	
			
			if ($post['ForumPost']['topic']) {			
				$this->data['ForumTopic']['subject'] = $topic['ForumTopic']['subject'];			
			}			
		} else {
			
			if ($post['ForumPost']['topic']) {
				$this->data['ForumTopic']['id'] = $topic['ForumTopic']['id'];
			}			
			$this->data['ForumPost']['id'] = $post['ForumPost']['id'];
			
			if ($this->ForumPost->saveAll($this->data)) {				
				$this->ForumCategory->updateLastTopic($forum_id);
				$this->redirect(array('controller' => 'forum', 'action' => 'topic', $topic_id, $forum_id));	
			}
		}
		
		$this->getForumPath($forum_id, $topic_id);
		
		$this->set('postID', $post_id);
		$this->set('topicID', $topic_id);
		$this->set('forumID', $forum_id);
		
		if ($post['ForumPost']['topic']) {
			$this->render('edittopic');
		}
		
	}
	
	/**
	 * Delete post
	 * only owner of post or admin/moderator 
	 */
	
	public function deletepost($post_id = null, $topic_id = null, $forum_id = null) {
		
		$post = $this->ForumPost->find('first', array(
				'conditions' => array('ForumPost.id' => $post_id, 'ForumPost.user_id' => $this->userID),
				'fields' => 'id, topic',
				'recursive' => -1
			)
		);
		
		if (empty($post)) {
			$this->redirect(array('controller' => 'forum', 'action' => 'index'));	
		}
				
		$topic = $this->ForumTopic->find('first', array(
				'conditions' => array('ForumTopic.id' => $topic_id),
				'fields' => 'replies',
				'recursive' => -1	
			)
		);
		
		$numPosts = $topic['ForumTopic']['replies'];
		$isTopic = $post['ForumPost']['topic'];
		

			
		if ($isTopic == 1 && $numPosts == 0 || $isTopic == 0 && $numPosts == 0) {		
			$this->deleteTopic($topic_id, $forum_id);		
		} else {		
			if($this->ForumPost->deletePost($post_id, $topic_id, $forum_id)) {
				$this->redirect(array('controller' => 'forum', 'action' => 'topic', $topic_id, $forum_id));
			}
		} 			
	}
	
/**
	 * Delete topic
	 * @param (int) topic ID
	 * @param (int) forum ID
	 */
	
	private function deleteTopic($topic_id = null, $forum_id = null) {		
			
		if ($this->ForumTopic->delete($topic_id)) {
			
			// update counters
			
			$this->ForumCategory->updateCounter('topics', '-', $forum_id);

			// update last topic data
			
			$this->ForumCategory->updateLastTopic($forum_id);
			
			$this->redirect(array('controller' => 'forum', 'action' => 'view', $forum_id));
		} else {
			$this->redirect(array('controller' => 'forum', 'action' => 'index'));
		}
	}
	
	/**
	 * Get forum path
	 *  forum name > topic name
	 */
	
	private function getForumPath($forum_id = null, $topic_id = null) {
		
		$this->set('topicSubject', $this->ForumTopic->getName($topic_id));
		$this->set('forumName', $this->ForumCategory->getName($forum_id));
		
	}
	
	/**
	 * Get number of last page
	 * @param topic ID
	 */
	
	public function getLastPageNumber($topic_id = null, $numPosts = null) {
		
		if (empty($numPosts)) {
			$this->ForumTopic->recursive = -1;
			$numPosts = $this->ForumTopic->read('replies', $topic_id);
		}
		
		if ($numPosts['ForumTopic']['replies'] <= $this->numPosts) {
			$numPage = 0;
		} else {
			if (($numPosts['ForumTopic']['replies'] % $this->numPosts) == 0) {
				$numPage = $numPosts['ForumTopic']['replies'] / $this->numPosts;
			} else {
				$numPage = floor($numPosts['ForumTopic']['replies'] / $this->numPosts) + 1; // page number for redirect
			}
		}
		
		return $numPage;
	}
	
	/**
	 * Unanswered topics
	 */
	
	public function unanswered() {
		
		$this->pageTitle = 'Unanswered topics';
		
		$this->ForumTopic->unbindModel(array('hasMany' => array('ForumPost')), false);
		
		$topics = $this->ForumTopic->find('all', array(
				'conditions' =>array('ForumTopic.replies' => '0'), 
				'order' => 'ForumTopic.created DESC',
				'limit' => $this->numTopics
			)
		);
		
		$this->set('topics', $topics);	
	}
	
	/**
	 * Convert <br /> to nl
	 */
	
	private function br2nl($string) {
    	return preg_replace('/\<br(\s*)?\/?\>/i', "", $string);
	}
	
}

?>