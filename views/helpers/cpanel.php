<?php
	/**
	* 
	*/
	class CpanelHelper extends Helper
	{
		var $helpers = array('Html', 'Form', 'Javascript', 'Tree');
		
		var $_branch;
		var $_root;
		
		function __construct() {
			$this->Cpanel =& Cpanel::getInstance();
			$this->Menu   =& ClassRegistry::init('Cpanel.CpanelMenu');
		}
		
		function beforeRender() {
			$this->menuTree = $this->Menu->find('threaded');
		}
		
		function getCrumbs() {
			$this->Html->addCrumb(__('Control Panel', true), $this->Cpanel->dashboardRoute);
			
			if (isset($this->params['section'])) {
				$section = $this->Menu->findByUrl($this->params['section']);
				$branch = $this->Tree->generate($this->menuTree, array('model' => 'CpanelMenu', 'element' => 'cpanel_menu_crumbs', 'autoPath' => array($section['CpanelMenu']['lft'], $section['CpanelMenu']['rght'])));
			} else {
				$controller = Inflector::humanize($this->params['controller']);
				
				if ($this->params['action'] == 'index') {
					$this->Html->addCrumb($controller);
				} else {
					$action = Inflector::humanize($this->params['action']);
					
					$this->Html->addCrumb($controller, array('action' => 'index'));
					$this->Html->addCrumb(Inflector::humanize($this->params['action']));
				}
			}
			
			return $this->Html->getCrumbs(' > ');
		}
		
		function gotoHome($msg = '') {
			$msg || $msg = __('Go to home page', true);
			
			return $this->Html->link($msg, '/');
		}
		
		function appCredentials() {
			$output = '';
			$output .= $this->Html->image(Configure::read('credentials.logo'));
			$output .= '<h1>' . Configure::read('credentials.title') . '</h1>';

			return $output;
		}
		
		function dashboard($text = null) {
			if (null === $text) {
				$text = __('Dashboard', true);
			}
			
			return $this->Html->link($text, $this->Cpanel->dashboardRoute, array('id' => 'dashboard'));
		}
		
		function account($text = null) {
			if (null === $text) {
				$text = __('My Account', true);
			}
			return $this->Html->link($text, /*$this->Cpanel->accountRoute*/'Account', array('id' => 'my-account'));
		}
		
		function logout($text = null) {
			if (null === $text) {
				$text = __('Logout', true);
			}
			return $this->Html->link($text, $this->Cpanel->logoutRoute, array('id' => 'logout'));
		}
		
		function levelUp() {
			
		}
		
		function sectionMenu() {
			if ($this->isCpanel()) {
				return;
			}
			
			$branch = $this->Cpanel->getpath($this->params['named']['section'], array('id', 'name', 'match_route'), 3);
			
			$firstLevelNodes = $this->Menu->find('all', array(
				'conditions' => array('parent_id' => $branch[0]['CpanelMenu']['id']),
				'fields' => array('id', 'parent_id', 'name', 'match_route'),
				'order' => 'id'
			));
			
			return $this->_buildMenu($firstLevelNodes);
		}
		
		function subsectionTabs() {
			
		}
		
		function sectionTitle($title = null) {
			return Inflector::humanize($this->params['controller']);
		}
		
		function newSectionLink($message = '', $options = array()) {
			return $this->Html->link($message, $this->Cpanel->newMenuSectionRoute, $options);
		}
		
		function menu() {
			$output = '';
			$output .= $this->_adminMenuTools();
			
			// Fetch sections from database
			// @todo Caching
			$sections = $this->Menu->getSectionsTree();
			
			$output .= $this->Tree->generate($sections, array('element' => 'cpanel_menu'));
			
			return $output;
		}
		
		function setSection(&$route, $value) {
			// Enforces not route through the plugin
			$route['plugin'] = null;
			$route['section'] = $value;
		}
		
		
		// Private
		function _adminMenuTools() {
			$output = '';
			if (Configure::read('debug')) {
				$output .= '<li>';
				$output .= $this->Html->link(__('Menu', true), $this->Cpanel->listMenuSectionsRoute);
				$output .= '</li>';
			}
			
			return $output;
		}
		
		function _urlIsActive($url) {
			return Router::url($url) == $this->here;
		}
		
		function _highlightSelected() {
			$block = <<<JS
			window.addEvent('domready', function(){
				var selected, parent;
				selected = $$('a[href=$this->here]');
				selected && selected.addClass('selected');
				
			});
JS;
			$this->Javascript->codeBlock($block, array('inline' => false));
		}
		
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
		
		function isCpanel() {
			return $this->params['plugin'] == $this->Cpanel->pluginName ? true : false;
		}
	}
	