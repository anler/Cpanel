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
		function setup() {
			if (!empty($this->data)) {
				if ($this->CpanelUser->setup($this->data)) {
					$this->Session->setFlash(__('The Root account has been created, now you can login.', true));
					$this->redirect(array('action' => 'login'));
				}
					$this->Session->setFlash(__('The Root account can\'t be created. Check if you fill the form correctly.', true));
					$this->data['User']['password'] = $this->data['User']['repassword'] = '';
			}
		}
		
		function account() {
			if (!empty($this->data)) {
				if ($this->CpanelUser->update($this->data)) {
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
	}
?>