<?php
	/**
	* 
	*/
	class CpanelHelper extends Helper
	{
		var $helpers = array('Html', 'Form', 'Javascript', 'Tree');
		
		var $_branch;
		var $_root;
		
		function beforeRender() {
			debug($this->params);exit;
			// $this->_highlightSelected();
			if (isset($this->params['section'])) {
				// $this->_setBranch($this->params['section']);
			}
		}
		
		function _setBranch($section) {
			debug($section);exit;
		}
		
		function getCrumbs() {
			$this->Html->addCrumb(__('Control Panel', true), ClassRegistry::init('Cpanel')->dashboardRoute);
			
			if ($this->isCpanel()) {
				($this->params['controller'] == 'control_panel') || $controller = Inflector::humanize($this->params['controller']);

				if (isset($controller)) {
					if ($this->params['action'] == 'index') {
						$this->Html->addCrumb($controller);
					} else {
						$this->Html->addCrumb($controller, array('action' => 'index'));
						$this->Html->addCrumb(Inflector::humanize($this->params['action']));
					}
				} else {
					$this->Html->addCrumb(Inflector::humanize($this->params['action']));
				}
			} else {
				if (!empty($this->params['named']['section'])) {
					$sections = ClassRegistry::init('CpanelMenu')->getpath($this->params['named']['section'], array('id', 'name', 'match_route'));
					
					if (!empty($sections)) {
						$last = array_pop($sections);
						foreach ($sections as $section) {
							$route = MenuItemRoute::unserializeRoute($section['CpanelMenu']['match_route'], true);
							// To keep track of crumbs
							// @todo Improve
							$this->setSection($route, $section['CpanelMenu']['id']);
							$this->Html->addCrumb($section['CpanelMenu']['name'], $route);
						}
						
						$this->Html->addCrumb($last['CpanelMenu']['name']);
					}
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
			
			return $this->Html->link($text, ClassRegistry::getObject('Cpanel')->dashboardRoute, array('id' => 'dashboard'));
		}
		
		function account($text = null) {
			if (null === $text) {
				$text = __('My Account', true);
			}
			return $this->Html->link($text, /*ClassRegistry::getObject('Cpanel')->accountRoute*/'Account', array('id' => 'my-account'));
		}
		
		function logout($text = null) {
			if (null === $text) {
				$text = __('Logout', true);
			}
			return $this->Html->link($text, ClassRegistry::init('Cpanel')->logoutRoute, array('id' => 'logout'));
		}
		
		function levelUp() {
			
		}
		
		function sectionMenu() {
			if ($this->isCpanel()) {
				return;
			}
			
			$branch = ClassRegistry::init('CpanelMenu')->getpath($this->params['named']['section'], array('id', 'name', 'match_route'), 3);
			
			$firstLevelNodes = ClassRegistry::init('CpanelMenu')->find('all', array(
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
			return $this->Html->link($message, ClassRegistry::init('Cpanel')->newMenuSectionRoute, $options);
		}
		
		function sections() {
			$output = '';
			
			$output .= $this->_adminMenuTools();
			
			// Fetch sections from database
			// @todo Caching
			$sections = ClassRegistry::init('Cpanel.CpanelMenu')->getSections();
			
			$element = $this->params['plugin'] == ClassRegistry::init('Cpanel')->pluginName ? 'menu' : '../../plugins/' . ClassRegistry::init('Cpanel')->pluginName . '/views/elements/menu';
			
			$output .= $this->Tree->generate($sections, array('element' => $element));
			
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
				$output .= $this->Html->link(__('Menu', true), ClassRegistry::getObject('Cpanel')->listMenuSectionsRoute);
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
			return $this->params['plugin'] == ClassRegistry::init('Cpanel')->pluginName ? true : false;
		}
	}
	