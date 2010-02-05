<?php
	/**
	* 
	*/
	class CpanelComponent extends Object
	{	
		var $components = array('Session');
		var $unauthorized = 'html/unauthorized';
		var $authorized = 'html/authorized';
		
		function initialize() {
			$viewPaths = Configure::read('viewPaths');
			array_unshift($viewPaths, APP . 'plugins' . DS . Cpanel::getInstance() . DS . 'views' . DS);
			Configure::write('viewPaths', $viewPaths);
		}
		
		function startup(&$controller) {
			$this->controller =& $controller;
			
			// Check if controller use AuthComponent
			if (!isset($controller->Auth)) {
				trigger_error(__('Your application must use Auth component in order to Cpanel work!', true), E_USER_ERROR);
			}
			
			if (!$this->_adminRequest()) {
				
				if ($this->_usingCpanel()) {
					// Deny direct access to controllers in the plugin
					$controller->redirect($controller->referer());
				} else {
					// Allow access if not is an admin action
					$controller->Auth->allow($controller->params['action']);
				}
				
			} else {
				// Save global configuration
				ClassRegistry::addObject('Cpanel', Cpanel::getInstance());
				
				// Configure Auth Component
				$controller->Auth->autoRedirect		 = false;
				$controller->Auth->loginAction		 = Cpanel::getInstance()->loginRoute;
				$controller->Auht->loginRedirect	 = Cpanel::getInstance()->dashboardRoute;
				
				// Enforce use of Cpanel function actions without 'Routing.admin' prefix
				if ($this->_usingCpanel()) {
					$controller->params['action'] = r(Cpanel::getInstance()->routingAdmin . '_', '', $controller->params['action']);
					
					foreach (array('login', 'setup') as $publicAdminAction) {
						($controller->params['action'] == $publicAdminAction) && $controller->Auth->allow($publicAdminAction);
						($controller->params['action'] == 'setup') && ($controller->Auth->authenticate = ClassRegistry::getObject('User'));
					}
				}
				
				// See if create the root account is needed
				// @todo Caching
				// 		 Check if users table exists
				$result = ClassRegistry::init('CpanelUser')->find('first', array('fields' => array('id')));
				$setupMode = Cpanel::getInstance()->setupMode = empty($result);
				
				// If setup needed, redirect to setup page
				if ($setupMode && !$this->_setupAction()) {
					$controller->redirect(Cpanel::getInstance()->setupRoute);
				}
				
				$controller->layout = $this->_layout();
			}

		}
		
		// Private
		function _adminRequest() {
			return isset($this->controller->params[Cpanel::getInstance()->routingAdmin]);
		}
		
		function _layout($plugin = true) {
			return $this->Session->read('Auth.User.id') ? $this->authorized : $this->unauthorized;
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
			$this->loginRoute				= array('controller' => $this->authController, 'action' => 'login', $this->routingAdmin => true, 'plugin' => $this->pluginName);
			$this->logoutRoute				= array('controller' => $this->authController, 'action' => 'logout', $this->routingAdmin => true, 'plugin' => $this->pluginName);
			$this->setupRoute				= array('controller' => $this->authController, 'action' => 'setup', $this->routingAdmin => true, 'plugin' => $this->pluginName);
			
			// Modules Route
			$this->dashboardRoute 			= array('controller' => $this->modulesController, 'action' => 'dashboard', $this->routingAdmin => true, 'plugin' => $this->pluginName);
			
			// Menu Routes
			$this->listMenuSectionsRoute	= array('controller' => $this->menuController, 'action' => 'index', $this->routingAdmin => true, 'plugin' => $this->pluginName);
			$this->newMenuSectionRoute		= array('controller' => $this->menuController, 'action' => 'add', $this->routingAdmin => true, 'plugin' => $this->pluginName);
			$this->editMenuSectionsRoute 	= array('controller' => $this->menuController, 'action' => 'edit', $this->routingAdmin => true, 'plugin' => $this->pluginName);
			$this->deleteMenuSectionRoute	= array('controller' => $this->menuController, 'action' => 'delete', $this->routingAdmin => true, 'plugin' => $this->pluginName);
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
	