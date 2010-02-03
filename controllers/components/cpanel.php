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
			// Check if controller use AuthComponent
			if (!@$controller->Auth) {
				trigger_error(__('Your application must use Auth component in order to Cpanel work!', true), E_USER_ERROR);
			}
			
			if (@!$controller->params[Cpanel::getInstance()->routingAdmin] && $controller->params['plugin'] != Cpanel::getInstance()->pluginName) {
				// Allow access if not is an admin action
				$controller->Auth->allow($controller->params['action']);
			} elseif ($controller->params['plugin'] == Cpanel::getInstance()->pluginName && @!$controller->params[Cpanel::getInstance()->routingAdmin]) {
				// Deny direct access to plugin controllers without authentication
				$controller->redirect($controller->referer());
			} else {
				// Save global configuration
				ClassRegistry::addObject('Cpanel', Cpanel::getInstance());
				
				// Set corresponding layout
				$controller->layout = $this->layout($controller->params['plugin']);
				
				// See if create the root account is needed
				// @todo Caching
				// 		 Check if users table exists
				$user = ClassRegistry::init('User');
				$result = ClassRegistry::init('User')->find('first', array('fields' => array('id')));
				$setupMode = Cpanel::getInstance()->setupMode = empty($result);

				// If setup needed, redirect to setup page
				if ($setupMode && $controller->action != Cpanel::getInstance()->routingAdmin . '_setup') {
					$controller->redirect(Cpanel::getInstance()->setupRoute);
				}
				
				// Configure Auth Component
				$controller->Auth->autoRedirect = false;
				$controller->Auth->loginAction = Cpanel::getInstance()->loginRoute;
				$controller->Auht->loginRedirect = Cpanel::getInstance()->dashboardRoute;
				
				// Enforce use of Cpanel actions without 'Routing.admin' prefix
				if ($controller->params[Cpanel::getInstance()->routingAdmin] && @$controller->params['plugin'] == Cpanel::getInstance()->pluginName) {
					$controller->params['action'] = str_replace(Cpanel::getInstance()->routingAdmin . '_', '', $controller->params['action']);
					
					foreach (array('login', 'setup') as $publicAdminAction) {
						($controller->params['action'] == $publicAdminAction) && $controller->Auth->allow($publicAdminAction);
						($controller->params['action'] == 'setup') && ($controller->Auth->authenticate = ClassRegistry::getObject('User'));
					}
				}
			}

			return 1;
		}
		
		function layout($plugin = true) {
			if (!$plugin) {
				// @todo This have to be improved
				$this->authorized = '../../plugins/' . Cpanel::getInstance()->pluginName . '/views/layouts/' . $this->authorized;
				$this->unauthorized = '../../plugins/' . Cpanel::getInstance()->pluginName . '/views/layouts/' . $this->unauthorized;
			}

			return $this->Session->read('Auth.User.id') ? $this->authorized : $this->unauthorized;
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
	}
	