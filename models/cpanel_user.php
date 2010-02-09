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
			),
			'currentpassword' => array(
				'rule' => array('passwordExists', 'username', 'currentpassword'),
				'required' => false,
				'message' => 'Invalid password for current user'
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
				$this->data[$this->name]['password'] = $this->hashPasswords($this->data[$this->name]['password'], true);
				$result = $this->save(null, false);
			}
			
			return $result;
		}
		
		function update($data) {
			$this->data = $data;
			
			if ($result = $this->validates()) {
				$this->read(null, $data[$this->name]['id']);
				$this->set('password', $this->hashPasswords($data[$this->name]['password'], true));
				$this->data[$this->name]['password'] = $this->hashPasswords($this->data[$this->name]['password'], null, true);
				$result = $this->save(null, false);
			}
			
			return $result;
		}
		
		function passwordsCompare($data, $password, $repassword) {
			return $data[$repassword] == $this->data[$this->name][$password];
		}
		
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
		
		function hashPasswords($password, $enforce = false) {
 			if (!empty($password) && $enforce) {
 				$password = Security::hash($password, null, true);
 			}

			return $password;
		}
	}
	