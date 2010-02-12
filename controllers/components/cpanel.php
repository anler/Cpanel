<?php
	/**
	* 
	*/
	class CpanelComponent extends Object
	{	
		var $components = array('Session');
		var $unauthorized = 'html/unauthorized';
		var $authorized = 'html/authorized';
		
		function initialize(&$controller) {
			$this->controller =& $controller;
			$viewPaths = Configure::read('viewPaths');
			array_unshift($viewPaths, APP . 'plugins' . DS . Cpanel::getInstance() . DS . 'views' . DS);
			Configure::write('viewPaths', $viewPaths);
			
			isset($this->userModel) || $this->userModel = ClassRegistry::init('CpanelUser');
			
			$this->_admin();
		}
		
		
		
		// Private
		function _admin() {
			// Check if controller use AuthComponent
			if (empty($this->controller->Auth)) {
				trigger_error(__('Your application must use Auth component in order to Cpanel work!', true), E_USER_ERROR);
			}
			
			if (!$this->_adminRequest()) {
				
				if ($this->_usingCpanel()) {
					// Deny direct access to controllers in the plugin
					$this->controller->redirect($this->controller->referer());
				} else {
					// Allow access if not is an admin action
					$this->controller->Auth->allow($this->controller->params['action']);
				}
				
			} else {
				// Configure Auth Component
				$this->controller->Auth->sessionKey	   = 'CpanelUser';
				$this->controller->Auth->autoRedirect  = false;
				$this->controller->Auth->loginAction   = Cpanel::getInstance()->loginRoute;
				$this->controller->Auht->loginRedirect = Cpanel::getInstance()->dashboardRoute;
				
				// See if create the root account is needed
				// @todo Caching
				// 		 Check if users table exists
				$result				= $this->userModel->find('first', array('fields' => array('id')));
				$this->setupMode 	= Cpanel::getInstance()->setupMode = empty($result);
				
				// If setup needed, redirect to setup page
				if ($this->setupMode && !$this->_setupAction()) {
					$this->controller->redirect(Cpanel::getInstance()->setupRoute);
				}
				
				if ($this->_usingCpanel()) {
					// Enforce use of Cpanel function actions without 'Routing.admin' prefix
					$this->controller->params['action'] = r(Cpanel::getInstance()->routingAdmin . '_', '', $this->controller->params['action']);
					
					// Deny direct access to plugin
					$this->controller->Auth->deny($this->controller->action);
					
					if ($this->_filterActionThroughWhitelist($this->controller->action)) {
						$this->controller->Auth->allow($this->controller->action);
					}
					
					if ($this->_filterActionThroughCustomAuth($this->controller->action)) {
						$this->controller->Auth->authenticate = $this->userModel;
					}
				}
				
				$this->controller->layout = $this->_layout();
			}

		}
		
		function _filterActionThroughWhiteList($action) {
			$allowed = array('login', 'password_forgotten');
			
			if ($this->setupMode) {
				$allowedOnSetup = array('setup');
				$allowed = array_merge($allowed, $allowedOnSetup);
			}
			
			$isAllowed = in_array($action, $allowed);
			
			return $isAllowed;
		}
		
		function _filterActionThroughCustomAuth($action) {
			$customAuthActions = array('setup', 'register_user', 'account');
			
			$customAuth = in_array($action, $customAuthActions);
			
			return $customAuth;
		}
		
		function _adminRequest() {
			return isset($this->controller->params[Cpanel::getInstance()->routingAdmin]);
		}
		
		function _layout($plugin = true) {
			return $this->controller->Auth->user('id') ? $this->authorized : $this->unauthorized;
		}
		
		function _usingCpanel() {
			return $this->controller->params['plugin'] == Cpanel::getInstance();
		}
		
		function _setupAction() {
			return Router::url(Cpanel::getInstance()->setupRoute) == $this->controller->here;
		}
	}
	
	
	/**
	* 
	*/
	class Cpanel 
	{
		static $_instance;
		
		private function __construct() {
			$this->pluginName			= 'cpanel';
			$this->routingAdmin			= Configure::read('Routing.admin');
			
			if (!$this->routingAdmin) {
				trigger_error('Cpanel needs Routing.admin to be activated!', E_USER_ERROR);
			}
			
			$this->setupMode 				= false;
			
			$this->modulesController 		= 'control_panel';
			$this->menuController			= 'menu';
			$this->authController	 		= 'users';
			
			$this->authModel				= 'User';
			
			// Auth Routes
			$this->loginRoute				= array('controller' => $this->authController, 'action' => 'login', $this->routingAdmin => true, 'plugin' => $this);
			$this->logoutRoute				= array('controller' => $this->authController, 'action' => 'logout', $this->routingAdmin => true, 'plugin' => $this);
			$this->setupRoute				= array('controller' => $this->authController, 'action' => 'setup', $this->routingAdmin => true, 'plugin' => $this);
			
			// Modules Route
			$this->dashboardRoute 			= array('controller' => $this->modulesController, 'action' => 'dashboard', $this->routingAdmin => true, 'plugin' => $this);
			
			// Menu Routes
			$this->listMenuSectionsRoute	= array('controller' => $this->menuController, 'action' => 'index', $this->routingAdmin => true, 'plugin' => $this);
			$this->newMenuSectionRoute		= array('controller' => $this->menuController, 'action' => 'add', $this->routingAdmin => true, 'plugin' => $this);
			$this->editMenuSectionsRoute 	= array('controller' => $this->menuController, 'action' => 'edit', $this->routingAdmin => true, 'plugin' => $this);
			$this->deleteMenuSectionRoute	= array('controller' => $this->menuController, 'action' => 'delete', $this->routingAdmin => true, 'plugin' => $this);
		}
		
		private function __clone() {}
		
		public static function getInstance() {
			if (!(self::$_instance instanceof self)) {
				self::$_instance = new self();
			}
			
			return self::$_instance;
		}
		
		public function __toString() {
			return self::getInstance()->pluginName;
		}
	}
	