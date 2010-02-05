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
					'message' => 'User name must be between 5 and 10 characters long'
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
				$this->data[$this->name]['id'] = 1;
				$this->data = $this->hashPasswords($this->data, true);
				$result = $this->save(null, false);
			}
			
			return $result;
		}
		
		function passwordsCompare($data, $password, $repassword) {
			return $data[$repassword] == $this->data[$this->name][$password];
		}
		
		function hashPasswords($data, $enforce = false) {
			debug('Debug Message');exit;
 			if (!empty($data[$this->name]['password']) && $enforce) {
 				$data[$this->name]['password'] = Security::hash($data[$this->name]['password'], null, true);
 			}

			return $data;
		}
	}
	