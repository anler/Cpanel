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
				if ($setupMode && $controller->action != Cpanel::getInstance()->routingAdmin . '_' . Cpanel::getInstance()->setupAction) {
					$controller->redirect(Cpanel::getInstance()->setupRoute);
				}
				
				// Configure Auth Component
				$controller->Auth->autoRedirect = true;
				$controller->Auth->loginAction = Cpanel::getInstance()->loginRoute;
				$controller->Auht->loginRedirect = Cpanel::getInstance()->dashboardRoute;
				
				// Enforce use of Cpanel actions without 'Routing.admin' prefix
				if ($controller->params[Cpanel::getInstance()->routingAdmin] && @$controller->params['plugin'] == Cpanel::getInstance()->pluginName) {
					$controller->params['action'] = str_replace(Cpanel::getInstance()->routingAdmin . '_', '', $controller->params['action']);
					
					foreach (array(Cpanel::getInstance()->loginAction, Cpanel::getInstance()->setupAction) as $publicAdminAction) {
						($controller->params['action'] == $publicAdminAction) && $controller->Auth->allow($publicAdminAction);
						($controller->params['action'] == Cpanel::getInstance()->setupAction) && ($controller->Auth->authenticate = ClassRegistry::getObject('User'));
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
			
			$this->modulesController 		= 'modules';
			$this->authController	 		= 'users';
			$this->authModel				= 'User';
			
			$this->loginAction				= 'login';
			$this->setupAction				= 'setup';
			
			$this->dashboard				= 'dashboard';
			$this->list_menu_sections		= 'list_menu_sections';
			// @todo change item for section
			$this->new_menu_item			= 'new_menu_item';
			$this->edit_menu_sections		= 'edit_menu_sections';
			
			$this->loginRoute				= array('controller' => $this->authController, 'action' => $this->loginAction, $this->routingAdmin => true, 'plugin' => $this->pluginName);
			$this->setupRoute				= array('controller' => $this->authController, 'action' => $this->setupAction, $this->routingAdmin => true, 'plugin' => $this->pluginName);
			$this->dashboardRoute 			= array('controller' => $this->modulesController, 'action' => $this->dashboard, $this->routingAdmin => true, 'plugin' => $this->pluginName);
			
			$this->listMenuSectionsRoute	= array('controller' => $this->modulesController, 'action' => $this->list_menu_sections, $this->routingAdmin => true, 'plugin' => $this->pluginName);
			// $this->newMenuItemRoute			= array('controller' => $this->modulesController, 'action' => $this->new_menu_item, $this->routingAdmin => true, 'plugin' => $this->pluginName);
			$this->newMenuSectionRoute		= array('controller' => $this->modulesController, 'action' => $this->new_menu_item, $this->routingAdmin => true, 'plugin' => $this->pluginName);
			$this->editMenuSectionsRoute 	= array('controller' => $this->modulesController, 'action' => $this->edit_menu_sections, $this->routingAdmin => true, 'plugin' => $this->pluginName);
		}
		
		private function __clone() {}
		
		public static function getInstance() {
			if (!(self::$_instance instanceof self)) {
				self::$_instance = new self();
			}
			
			return self::$_instance;
		}
	}
	