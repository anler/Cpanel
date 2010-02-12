<?php
	/**
	* 
	*/
	class UsersController extends CpanelAppController {
		var $uses = array('Cpanel.CpanelUser');
		
		var $components = array('RequestHandler');
		
		var $helpers = array('Time');
		
		function beforeFilter() {
			$this->Auth->userModel = 'CpanelUser';
		}
		
		/**
		 * 
		 */
		function index() {
			$this->set('users', $this->CpanelUser->findAll());
		}
		
		/**
		 * 
		 */
		function register_user() {
			if (!empty($this->data)) {
				
				$this->data['CpanelUser']['repassword'] = $this->data['CpanelUser']['password'] = $this->CpanelUser->getRandomizedPassword();
				
				if ($this->CpanelUser->save($this->data)) {
					if (!$this->_sendPasswordByEmail($this->data['CpanelUser']['username'], $this->data['CpanelUser']['email'], $this->data['CpanelUser']['password'])) {
						$this->Session->setFlash(__('Email could not be sended right now. Please, try again later', true), $this->notice);
					} else {
						$this->Session->setFlash(__('Account created', true), $this->success);
					}
					
					$this->redirect(array('action' => 'index'));
				}
				
				$this->Session->setFlash(__('Some errors prevented create the user account.', true));
			}
		}
		
		function password_forgotten() {
			if ($this->Auth->user()) {
				$this->redirect(array('action' => 'account'));
			}
			
			if (!empty($this->data)) {
				if ($user = $this->CpanelUser->findByUsername($this->data['CpanelUser']['username'])) {
					
					if ($newPassword = $this->CpanelUser->resetPassword($user['CpanelUser']['id'])) {
						
						if ($emailSended = $this->_sendPasswordByEmail($user['CpanelUser']['username'], $user['CpanelUser']['email'], $newPassword)) {
							$this->render('password_changed');
						}
						
						$this->Session->setFlash(__('We can\'t email you the password right now. Please, try again later', true), $this->notice);
						
					} else {
						$this->Session->setFlash(__('Password could not be saved. Please, try again.', true), $this->failure);
					}
					
				} else {
					$this->Session->setFlash(__('The user name provided not exists', true), $this->failure);
				}
			}
		}
		
		/**
		 * 
		 */
		function reset_password($id = null) {
			$this->_redirectIfInvalid($id);
			
			if (!empty($this->data)) {
				
			}
		}
		
		/**
		 * 
		 */
		function setup() {
			if (!empty($this->data)) {
				if ($this->CpanelUser->save($this->data)) {
					$this->Session->setFlash(__('The Root account has been created, now you can login.', true));
					$this->redirect(array('action' => 'login'));
				}
				$this->Session->setFlash(__('Some errors prevented create the Root account.', true));
				$this->data['CpanelUser']['password'] = $this->data['CpanelUser']['repassword'] = '';
			}
		}
		
		function account() {
			if (!empty($this->data)) {
				if ($this->CpanelUser->save($this->data)) {
					$this->Session->setFlash(__('Changes Saved', true), $this->success);
				} else {
					$this->Session->setFlash(__('Changes not saved', true), $this->failure);
				}
				
				unset($this->data['CpanelUser']);
			}
			
			$username 		= $this->Session->read('CpanelUser.username');
			$lastLogin 		= $this->Session->read('CpanelUser.last_login');
			$lastLoginIP	= $this->Session->read('CpanelUser.last_login_ip');
			
			$this->set(compact('username', 'lastLogin', 'lastLoginIP'));
		}
		
		/**
		 * 
		 */
		function login() {
			if ($this->Auth->user('id')) {
				if ($this->Session->read('CPanelUser.last_login') == '0000-00-00 00:00:00') {
					$this->Session->write('CPanelUser.last_login', __('This is the first time you log in', true));
				}

				$this->CpanelUser->read(array('last_login', 'last_login_ip'), $this->Session->read('CpanelUser.id'));
				$this->CpanelUser->set('last_login', date('Y-m-d H-i-s'));
				$this->CpanelUser->set('last_login_ip', $this->RequestHandler->getClientIP());
				$this->CpanelUser->save(null, false);
				
				$this->redirect(array('controller' => 'control_panel', 'action' => 'dashboard'));
			}
		}
		
		/**
		 * 
		 */
		function logout() {
			$this->redirect($this->Auth->logout());
		}
		
		/**
		 * 
		 */
		function delete($id = null) {
			$this->_redirectIfInvalid($id, __('Invalid id for user', true));
			
			if ($this->CpanelUser->delete($id)) {
				$this->Session->setFlash(__('User deleted', true), $this->success);
			} else {
				$this->Session->setFlash(__('User cannot be deleted', true), $this->failure);
			}
			
			$this->redirect($this->referer());
		}
		
		// Private
		
		/**
		 * 
		 */
		function _sendPasswordByEmail($username, $email, $password) {
			if (!array_key_exists('Email', $this->components)) {
				trigger_error('CPanel::UsersController - Email component is required', E_USER_ERROR);
			}
			
			$this->Email->to = $email;
			$this->Email->subject = 'Test Credentials';
			$this->Email->from = 'noreply@test.com';
			
			$this->set(compact('username', 'password'));

			$this->Email->send();
			$sended = !$this->Email->smtpError;
			
			return $sended;
		}
	}
?>