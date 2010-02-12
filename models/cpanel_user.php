<?php
	/**
	* 
	*/
	class CpanelUser extends CpanelAppModel
	{
		var $validate = array(
			'username' => array(
				'maxLength' => array(
					'rule' => array('between', 5, 10),
					'message' => 'must be between 5 and 10 characters long'
				),
				'unique' => array(
					'rule' => 'isUnique',
					'message' => 'already exists'
				)
			),
			'email' => array(
				'unique' => array(
					'rule' => 'isUnique',
					'message' => 'already exists'
				),
				'valid' => array(
					'rule' => 'email',
					'message' => 'not seems to be a valid address'
				)
			),
			'password' => array(
				'rule' => array('minLength', 4),
				'message' => 'must be at least 4 characters long'
			),
			'repassword' => array(
				'rule' => array('passwordsCompare', 'password', 'repassword'),
				'message' => 'not match password'
			),
			'currentpassword' => array(
				'rule' => array('passwordExists', 'username', 'currentpassword'),
				'required' => false,
				'message' => 'Invalid password for current user'
			)
		);
		
		/**
		 * 
		 */
		// function initialized() {
		// 			return $this->find('first', array('fields' => array('id')));
		// 		}
		
		function beforeSave($options = array()) {
			if (!empty($this->data[$this->name]['password'])) {
				$hashedPassword = $this->hashPasswords($this->data[$this->name]['password'], true);
				$this->set('password', $hashedPassword);
			}
			
			return true;
		}
		
		/**
		 * 
		 */
		// function setup($data) {
		// 	$this->create($data);
		// 	
		// 	if ($result = $this->validates()) {
		// 		$hashedPassword = $this->hashPasswords($this->data[$this->name]['password'], true);
		// 		
		// 		// Enforces root user to id of 1
		// 		$this->set('id', 1);
		// 		$this->set('password', $hashedPassword);
		// 		
		// 		$result = $this->save(null, false);
		// 	}
		// 	
		// 	return $result;
		// }
		
		/**
		 * 
		 */
		// function register($data) {
		// 	$this->create($data);
		// 	
		// 	if ($result = $this->validates()) {
		// 		$hashedPassword = $this->hashPasswords($this->data[$this->name]['password'], true);
		// 		$this->set('password', $hashedPassword);
		// 		
		// 		$result = $this->save(null, false);
		// 	}
		// 	
		// 	return $result;
		// }
		
		/**
		 * 
		 */
		// function update($data) {
		// 	$this->data = $data;
		// 	
		// 	if ($result = $this->validates()) {
		// 		$this->read(null, $data[$this->name]['id']);
		// 		$this->set('password', $this->hashPasswords($data[$this->name]['password'], true));
		// 		$this->data[$this->name]['password'] = $this->hashPasswords($this->data[$this->name]['password'], null, true);
		// 		$result = $this->save(null, false);
		// 	}
		// 	
		// 	return $result;
		// }
		
		/**
		 * 
		 */
		// function getMailIfUserExists($username) {
		// 	$email = '';
		// 	
		// 	if ($this->data = $this->findByUsername($username)) {
		// 		$email = $this->data[$this->name]['email'];
		// 	}
		// 	
		// 	return $email;
		// }
		
		/**
		 * 
		 */
		function resetPassword($id) {
			$this->read(array('password'), $id);
			
			$randomPassword = $this->getRandomizedPassword();
			$newPassword 	= $this->hashPasswords($randomPassword, true);
			$this->set('password', $this->hashPasswords($newPassword));
			
			$success = false;
			if ($this->save()) {
				$success = $newPassword;
			}
			
			return $success;
		}
		
		/**
		 * 
		 */
		function getRandomizedPassword() {
			$randomPassword = rand() + time() + 42 + Configure::read('Security.salt');
			
			return $randomPassword;
		}
		
		/**
		 * 
		 */
		function passwordsCompare($data, $password, $repassword) {
			return $data[$repassword] == $this->data[$this->name][$password];
		}
		
		/**
		 * 
		 */
		function passwordExists($data, $username, $password) {
			$finded = false;
			
			if (!empty($this->data)) {
				$username = $this->data[$this->name][$username];
				$hashedPassword = $this->hashPasswords($data[$password], true);
				$row = $this->find('first', array('conditions' => array('username' => $username, 'password' => $hashedPassword)));
				$finded = !empty($row);
			}
			
			return $finded;
		}
		
		/**
		 * 
		 */
		function hashPasswords($password, $enforce = false) {
 			if (!empty($password) && $enforce) {
 				$password = Security::hash($password, null, true);
 			}

			return $password;
		}
	}
?>