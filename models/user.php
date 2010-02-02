<?php
	/**
	* 
	*/
	class User extends CpanelAppModel
	{
		var $validate = array(
			'username' => array(
				'maxLength' => array(
					'rule' => array('between', 5, 10),
					'message' => 'Username must be between 5 and 10 characters long'
				)
			),
			'password' => array(
				'rule' => array('minLength', 4),
				'message' => 'Password must be at least 4 characters long'
			),
			'repassword' => array(
				'rule' => array('passwordsCompare', 'password', 'repassword'),
				'message' => 'Passwords does\'nt match'
			)
		);
		
		function initialized() {
			return $this->find('first', array('fields' => array('id')));
		}
		
		function setup($data) {
			$this->create($data);
			
			if ($result = $this->validates()) {
				// Enforces root user to id of 1
				$this->data['User']['id'] = 1;
				$this->data = $this->hashPasswords($this->data, true);
				$result = $this->save(null, false);
			}
			
			return $result;
		}
		
		function passwordsCompare($data, $password, $repassword) {
			return $data[$repassword] == $this->data['User'][$password];
		}
		
		function hashPasswords($data, $enforce = false) {
 			if (!empty($data['User']['password']) && $enforce) {
 				$data['User']['password'] = Security::hash($data['User']['password'], null, true);
 			}

			return $data;
		}
	}
	