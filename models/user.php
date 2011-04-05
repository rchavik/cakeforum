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

class User extends AppModel {
	
	public $validate = array(

		    'username' => array(
				'checkunique' => array('rule' => array('checkUnique', 'username')),
				'alphanumeric' => array('rule' => array('custom', '/^[a-zA-Z0-9_-]{3,25}$/')),
				//'alphanumeric' => array('rule' => 'alphaNumeric'),
				'required' => array('rule' => 'notempty')
    		),

    		'email' => array(
    			'checkunique' => array('rule' => array('checkUnique', 'email')),
        		'validemail' => array('rule' => 'email'),
    			'required' => array('rule' => 'notempty')
    		),
    		
			// copy of password field
			
    		'form_password' => array(
				'required' => array(
					'rule' => 'notempty',
					'on' => 'create'
    			),					
    			'lenght' => array(
    				'rule' => array('minLength', 3),
    				'allowEmpty' => true
    			),
    		),

	    	'confirm_password' => array(
    			'confirm' => array('rule' => 'confirmPassword'),
				'required' => array(
					'rule' => 'notempty',
					'on' => 'create'
    			),	
    			'lenght' => array(
    				'rule' => array('minLength', 3),
    				'allowEmpty' => true
    			)
    		),
    		
    		'old_password' => array(
     			'rule' => 'checkOldPassword',
    			'allowEmpty' => true 		
    		),

    		// email for lost password
    		'lpemail' => array(
    			'checkunique' => array('rule' => array('lostPassword')),
        		'validemail' => array('rule' => 'email'),
    			'required' => array('rule' => 'notempty')
    		),
  		
	);
    	
	/**
	 * Associations
	 */
	
	public $belongsTo = array('Group');
	
	/**
	 * Callback
	 */
	
	public function beforeValidate() {		
		if (!empty($this->data['User']['form_password'])) {
			$this->validate['old_password']['allowEmpty'] = false;
		}
	}
	
	
	/**
	 * Check confim password
	 * @return boolean
	 */
	
	public function confirmPassword($value) {
		if ($this->data['User']['form_password'] == $value['confirm_password']) {
			return true;
		}
		return false;
	}

	/**
	 * Check if email exist in database
	 * @return boolean
	 */

	public function lostPassword() {
		
		$count = $this->find('count', array(
				'conditions' => array('User.email' => $this->data['User']['lpemail']),
				'recursive' => -1
			)
		);
		
		if ($count > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * Check is data unique
	 * @param data
	 * @param field name
	 * @return boolean
	 */

	public function checkUnique($data, $fieldName) {
		
		if (isset($this->data['User']['id'])) {
			
			$num = $this->find('count', array(
				'conditions' => array(
					'User.' . $fieldName => $data[$fieldName],
					'User.id <>' => $this->data['User']['id']
					),
				'recursive' => -1
				)
			);
			
			if ($num > 0) {
				return false;
			} else {
				return true;
			}
		} else {
			$valid = false;
			if (isset($fieldName) && $this->hasField($fieldName)) {
				$valid = $this->isUnique(array($fieldName => $data[$fieldName]));
			}
			return $valid;
		}
	}
	
	public function checkOldPassword($data) {
		
		$user = $this->find('first', array(
				'conditions' => array('id' => $this->data['User']['id']),
				'fields' => array('password'),
				'recursive' => -1
			)
		);
		
		if (Security::hash($data['old_password'], null, true) == $user['User']['password']) {
			return true;
		} else {
			return false;
		}
	}	

	
}
?>