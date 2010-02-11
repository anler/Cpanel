<?php
	/**
	* 
	*/
	class CpanelHelper extends Helper {
		var $helpers = array('Html', 'Form', 'Javascript', 'Tree');
		
		function __construct() {
			$this->Cpanel =& Cpanel::getInstance();
			$this->Menu   =& ClassRegistry::init('Cpanel.CpanelMenu');
		}
		
		// General
		/**
		 * 
		 */
		function appCredentials($attrs = array()) {
			$output = '';
			
			$appName = Configure::read('credentials.title');
			$appImg = Configure::read('credentials.logo');
			
			$image = $this->Html->image($appImg);
			$header = $this->Html->tag('h1', $appName);
			
			$output .= sprintf($this->Html->tags['block'], $this->_parseAttributes($attrs), "\n$image\n$header\n");

			return $output;
		}
		
		/**
		 * 
		 */
		function userActions($attrs = array()) {
			$output = '';
			
			$dashboard 	= $this->dashboard();
			$account	= $this->account();
			$logout 	= $this->logout();
			
			$links = array();
			foreach (array($dashboard, $account, $logout) as $link) {
				$links[] = sprintf($this->Html->tags['li'], null, "\n$link\n");
			}
			
			if (!empty($links)) {
				$output .= sprintf($this->Html->tags['ul'], $this->_parseAttributes($attrs), implode("\n", $links));
			}
			
			return $output;
		}
		
		/**
		 * 
		 */
		function css() {
			$file = new File(CSS_URL . 'cpanel.css');
			
			$css = $this->Html->css('/cpanel/css/cpanel.css');
			
			if ($file->exists()) {
				$css = $this->Html->css('cpanel.css');
			}
			
			return $css;
		}
		
		/**
		 * 
		 */
		function dashboard($text = null) {
			if (null === $text) {
				$text = __('Dashboard', true);
			}
			
			$attrs = array('id' => 'dashboard', 'class' => 'top-options');
			
			if ($this->params['plugin'] == Cpanel::getInstance()
					&& $this->params['action'] == 'dashboard') {
				$attrs['class'] .= ' active';
			}
			
			$output = $this->Html->link($text, array('controller' => 'control_panel', 'action' => 'dashboard'), $attrs);
			return $output;
		}
		
		/**
		 * 
		 */
		function account($text = null) {
			if (null === $text) {
				$text = __('My Account', true);
			}
			
			$attrs = array('id' => 'my-account', 'class' => 'top-options');
			
			if ($this->params['plugin'] == Cpanel::getInstance()
					&& $this->params['action'] == 'account') {
				$attrs['class'] .= ' active';
			}
			
			$output = $this->Html->link($text, array('controller' => 'users', 'action' => 'account'), $attrs);
			return $output;
		}
		
		/**
		 * 
		 */
		function logout($text = null) {
			if (null === $text) {
				$text = __('Logout', true);
			}
			
			return $this->Html->link($text, $this->Cpanel->logoutRoute, array('id' => 'logout'));
		}
		
		// Navigation
		/**
		 * 
		 */
		function navigationList($attrs = array()) {
			$output = '';
			
			$output .= $this->_adminMenuTools();
			
			$autoPath = array();
			$sectionId = null;
			
			$section = $this->Menu->findSectionPathFromUrl($this->_getSection());
			
			if (!empty($section)) {
				$autoPath = array($section['CpanelMenu']['lft'], $section['CpanelMenu']['rght']);
				$sectionId = $section['CpanelMenu']['id'];
			}
			
			// sectionPath is the path (tree) generated when a
			// nested node selected
			$sectionPath = $this->Menu->findSectionPath($sectionId);
			
			if (!empty($sectionPath)) {
				$output .= $this->Tree->generate($sectionPath, array('model' => 'CpanelMenu', 'element' => 'menu/cpanel_menu', 'autoPath' => $autoPath));
			}
			
			$output = sprintf($this->Html->tags['ul'], $this->_parseAttributes($attrs), "\n$output\n");
			
			return $output;
		}
		
		// Content Navigation
		/**
		 * 
		 */
		function crumbs($separator = '&nbsp;&raquo;&nbsp;', $attrs = array()) {
			$this->Html->addCrumb(__('Control Panel', true), $this->Cpanel->dashboardRoute);
			
			if (isset($this->params['section'])) {
				$section = $this->Menu->findSectionPathFromUrl($this->params['section']);
				
				$branch = $this->Tree->generate(
							$section['path'],
							array(
								'model' => 'CpanelMenu',
								'element' => 'menu/cpanel_menu_crumbs',
								'autoPath' => array($section['CpanelMenu']['lft'], $section['CpanelMenu']['rght'])
							)
						);
			} else {
				// Crumbs only for plugin's controllers
				if ($this->params['controller'] != 'control_panel') {
					$controller = Inflector::humanize($this->params['controller']);
					
					if ($this->params['action'] == 'index') {
						$this->Html->addCrumb($controller);
					} else {
						$action = Inflector::humanize($this->params['action']);
						$this->Html->addCrumb($controller, array('action' => 'index'));
						$this->Html->addCrumb(Inflector::humanize($this->params['action']));
					}
				} else {
					$this->Html->addCrumb(Inflector::humanize($this->params['action']));
				}
			}
			
			$breadcrumbs = sprintf($this->Html->tags['block'], $this->_parseAttributes($attrs), $this->Html->getCrumbs($separator));
			return $breadcrumbs;
		}
		
		/**
		 * @todo Implement method
		 */
		function levelUp() {
			
		}
		
		/**
		 * 
		 */
		function sectionTabs($attrs = array()) {
			if ($this->isCpanel()) {
				return;
			}
			
			$attrs['element']	= 'menu/cpanel_menu';
			$attrs['model']		= 'CpanelMenu';
			
			$output = $this->_buildTabs(array('depth' => 0, 'autoPath' => true), $attrs);
			
			return $output;
		}
		
		/**
		 * 
		 */
		function sectionSubTabs($attrs = array()) {
			if ($this->isCpanel()) {
				return;
			}
			
			$output = '';
			
			$attrs['element']	= 'menu/cpanel_menu';
			$attrs['model']		= 'CpanelMenu';
			
			$output .= $this->_buildTabs(array('depth' => 1, 'autoPath' => true), $attrs);
			
			return $output;
		}
		
		/**
		 * 
		 */
		function sectionActions($attrs = array()) {
			if ($this->isCpanel()) {
				return;
			}
			
			// Little hack, improve in future
			$section = $this->Menu->findSectionPathFromUrl($this->_getSection());
			$triggerDepth = 1;
			if (empty($section['path'][$triggerDepth])) {
				return;
			}
			
			$output = '';
			
			$actions = $this->_findControllerActions($this->Menu->findSectionFromUrl($this->params['section']));
			
			$links = array();
			foreach ($actions as $action) {
				$link = $this->Html->link($action['name'], $action['route']);
				$links[] = sprintf($this->Html->tags['li'], null, $link);
			}
			
			if (!empty($links)) {
				$output .= sprintf($this->Html->tags['ul'], $this->_parseAttributes($attrs), implode("\n", $links));
			}
			
			return $output;
		}
		
		// Content
		/**
		 * 
		 */
		function sectionTitle($attrs = array()) {
			$title = isset($this->params['section']) ? 
							Inflector::humanize($this->params['section']) 
							:
							Inflector::humanize($this->params['controller']);
			
			$output = $this->Html->tag('h2', $title, $attrs);
			
			return $output;
		}
		
		// Configuration
		function setSection(&$route, $value) {
			// Enforces not route through the plugin
			$route['plugin'] = null;
			$route['section'] = $value;
		}
		
		/**
		 * 
		 */
		function newSectionLink($message = '', $options = array()) {
			return $this->Html->link($message, array('action' => 'add'), $options);
		}
		
		// Private
		/**
		 * 
		 */
		function _adminMenuTools() {
			$output = '';
			if (Configure::read('debug')) {
				$attrs = array();
				
				if ($this->params['plugin'] == $this->Cpanel->pluginName
						&& $this->params['controller'] == 'menu') {
					$attrs['class'] = 'active';
				}
				
				$link = $this->Html->link(__('Menu', true), array('controller' => 'menu'));
				
				$output = sprintf($this->Html->tags['li'], $this->_parseAttributes($attrs), $link);
			}
			
			return $output;
		}
		
		/**
		 * 
		 */
		function _getSection() {
			$section = isset($this->params['section']) ? $this->params['section'] : null;
			
			return $section;
		}
		
		/**
		 * 
		 */
		function _buildMenu($nodes, $options = array()) {
			$nodes = (array) $nodes;
			
			$links = array();
			
			foreach ($nodes as $node) {
				$nodeName = $node['CpanelMenu']['name'];
				$nodeRoute = MenuItemRoute::unserializeRoute($node['CpanelMenu']['match_route'], true);
				$this->setSection($nodeRoute, $node['CpanelMenu']['id']);
				
				$links[] = sprintf($this->Html->tags['li'], '', $this->Html->link($nodeName, $nodeRoute));
			}
			
			return sprintf($this->Html->tags['ul'], $this->_parseAttributes($options), implode("\n", $links));
		}
		
		/**
		 * 
		 */
		function isCpanel() {
			return $this->params['plugin'] == $this->Cpanel->pluginName ? true : false;
		}
		
		/**
		 * 
		 */
		function _buildTabs($options = array(), $treeOptions = array()) {
			$result = '';
			
			$options = Set::merge(
				// Specify the depth of the parent which
				// from you want his children
				array('depth' => 0, 'autoPath' => false),
				$options
			);
			
			$section = $this->Menu->findSectionPathFromUrl($this->_getSection());
			$tabsDepth = $options['depth'];
			
			if ($options['autoPath']) {
				$treeOptions['autoPath'] = array($section['CpanelMenu']['lft'], $section['CpanelMenu']['rght']);
			}
			
			if (!empty($section) && isset($section['path'][$tabsDepth])) {
				$tabs = $this->Menu->findAllByParentId($section['path'][$tabsDepth]['CpanelMenu']['id']);
				$result = $this->Tree->generate($tabs, $treeOptions);
			}
			
			return $result;
		}
		
		/**
		 * 
		 */
		function _findControllerActions($section) {
			$route = MenuItemRoute::unserializeRoute($section['CpanelMenu']['match_route'], 'asArray');
			
			$controller = isset($route['controller']) ? $route['controller'] : $this->params['controller'];
			
			$actions = array();
			
			// Find all Admin public actions for this controller
			App::import('Controller', $route['controller']);
			
			try {
				$className = Inflector::camelize($route['controller'] . '_controller');
				$ClassInstance = new ReflectionClass($className);
				$methodCollection = $ClassInstance->getMethods();
				
				$routingAdmin = Configure::read('Routing.admin');
				$section = $this->params['section'];
				
				foreach ($methodCollection as $MethodObject) {
					// Filter only prefixed methods
					if (!preg_match("/^$routingAdmin\_.*/", $MethodObject->name)) {
						continue;
					}
					
					$action = trim(r("$routingAdmin", '', $MethodObject->name), '_ ');
					$actionName = $action == 'index' ? __('List', true) : Inflector::humanize($action);
					$actions[] = array(
						'name' => $actionName,
						'route' => array('controller' => $controller, 'action' => $action, 'section' => $section)
					);
					
				}
			} catch (Exception $e) {
				$this->log('ExceptionRaised in CpanelHelper.php:124 with message: ' . $e->getMessage());
				return;
			}
			
			return $actions;
		}
	}
	