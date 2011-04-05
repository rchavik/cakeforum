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

class UsersController extends AppController {
	
	public $uses = array('User', 'Group');
	
	public $components = array('CfEmail');
	
	public $helpers = array('Time');



	public function beforeFilter() {

		parent::beforeFilter();
		
		$this->Auth->allowedActions = array(
			'register', 'registered', 'login', 'logout', 'lostpassword', 'confirm', 'reset', 'public_profile'
		);

		// copy of form_password to password for auto hashing in Auth Component
		if (isset($this->data['User']['form_password']) && !empty($this->data['User']['form_password'])) {
			$this->data['User']['password'] = $this->data['User']['form_password'];
		}		
	}

	/**
	 * Public
	 */
	
	public function profile($username) {
		
		$this->pageTitle = $username;
		
		$this->User->unbindModel(array('belongsTo' => array('Group')));
		
		$user = $this->User->find('first', array(
				'conditions' =>array('User.username' => $username),
				'fields' => array('User.id', 'User.username', 'User.email', 'User.created')
		));		

		$this->set('data', $user);		
	}
	
	/**
	 * Register
	 */

	public function register() {
		
		$this->pageTitle = 'Register';

		if (empty($this->userID)) {
			if (!empty($this->data)) {
				
				$this->data['User']['group_id'] = 2;
				$this->data['User']['active'] = 1;
				
				// save user data
				
				if ($this->User->save($this->data)) {				
					$this->redirect('/registered');				
				} else {
					$this->data['User']['form_password'] = '';
					$this->data['User']['confirm_password'] = '';
				}
			}
		} else {
			// if user logged in redirect to home page
			$this->redirect('/');
		}
	}
	
	/**
	 * Confirmation message after registration
	 */
	
	public function registered() {
		
	}
	
	/**
	 * Login
	 */

	public function login() {
		
		$this->pageTitle = 'Login';

		if (! empty($this->userID) && $this->Session->valid()) {
			$this->redirect('/');
		}
		$this->set('error', false);
		if (! empty($this->data)) {
			if ($this->Auth->login()) {
				$this->redirect('/');			
			} else {
				$this->Session->setFlash("Wrong username or password.");
			}
		}
	}
	
	/**
	 * Logout
	 */

	public function logout() {
		$this->Auth->logout();
		$this->redirect('/');
	}
	
	/**
	 * Lost password
	 */

	public function lostpassword() {
		
		$this->pageTitle = 'Lost password';

		if (!empty($this->data)) {

			$user = $this->User->find('first', array(
					'conditions' => array('email' => $this->data['User']['email'], 'active' => '1'), 
					'fields' => array('User.id', 'User.username', 'User.email'), 
					'recursive' => -1
				)
			);
			
			if(!empty($user)) {
				$user['User']['confirm'] = String::uuid();
				// foolproof: check if the confirm string is unique
				while($this->User->find('count', array(
							'conditions' => array('User.confirm' => $user['User']['confirm']),
							'recursive' => -1
							)
						)
					) {
					$user['User']['confirm'] = String::uuid();
				}
				$this->User->id = $user['User']['id'];
				if ($this->User->saveField('confirm', $user['User']['confirm'])) {
							
					// send confirmation mail
					$this->set('data', $user['User']);
					$this->set('host', $_SERVER['HTTP_HOST'] . '/' . Configure::read('CfFolder'));

					if( $this->CfEmail->send($user['User']['email'], 'CakeForum lost password', 'lostpassword') ) {
						$message = 'The confirmation email has been sent, please check your inbox.';
					} else {
						$message = 'E-mail could not be sent at this time.';
					}
				}
			} else {
				$message = 'User not found or not active';
			}
			$this->Session->setFlash($message);
			$this->redirect('/lostpassword');
		}
	}
	
	public function reset($string) {
	
		$user = $this->User->find('first', array(
				'conditions' => array('confirm' => $string), 
				'fields' => array('User.id', 'User.username', 'User.email'), 
				'recursive' => -1
			)
		);

		if (!empty($user)) {
			
			$password = $this->randomPassword();
			
			$this->data['User']['id'] = $user['User']['id'];		
			$this->data['User']['password'] = Security::hash($password, null, true);
			$this->data['User']['confirm'] = '';
			
			if ( $this->User->save($this->data) ) {
				// send mail
				$this->set('data', $user['User']);
				$this->set('password', $password);
				$this->set('host', $_SERVER['HTTP_HOST']);
				if( $this->CfEmail->send($user['User']['email'], 'CakeForum new password', 'newpassword') ) {
					$message = 'E-mail with new password has been sent, please check your inbox.';
				} else {
					$message = 'E-mail could not be sent at this time.';
				}
			}
		} else {
			$message = 'Error.';
		}
		
		$this->Session->setFlash($message);
		$this->redirect('/login');
	
	}

	private function randomPassword() {
		return substr(md5(rand(123456, 98765402)), 10, 8);
	}
	
	public function account() {	

		$this->pageTitle = 'Account';
		
		if (empty($this->data)) {		
			$this->data = $this->User->find('first', array(
					'conditions' => array('id' => $this->userID),
					'fields' => array('id', 'username', 'email'),
					'recursive' => -1
				)
			);	
		} else {			
			$this->data['User']['id'] = $this->userID;	

			$fieldList = array('username', 'email', 'password', 'old_password', 'form_password', 'confirm_password');
			if($this->User->save($this->data, true, $fieldList)) {
				$this->Session->setFlash("Account data has been saved.");
				$this->redirect('/account');
			}
		}	
	}
}
?>