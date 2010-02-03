<?php
	/**
	* 
	*/
	class CpanelHelper extends Helper
	{
		var $helpers = array('Html', 'Form', 'Tree');
		
		function getCrumbs() {
			$this->Html->addCrumb(__('Control Panel', true), ClassRegistry::init('Cpanel')->dashboardRoute);
			
			if ($this->params['plugin'] == ClassRegistry::init('Cpanel')->pluginName) {
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
					foreach ($sections as $section) {
						$route = unserialize($section['CpanelMenu']['match_route']);
						debug($route);exit;
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
			return $this->Html->link($text, '', array('id' => 'my-account'));
		}
		
		function logout($text = null) {
			if (null === $text) {
				$text = __('Logout', true);
			}
			return $this->Html->link($text, ClassRegistry::init('Cpanel')->logoutRoute, array('id' => 'logout'));
		}
		
		function levelUp() {
			
		}
		
		function sectionTabs() {
			
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
			
			// $output .= $this->_organize($items, 2); - I found the tree helper does this and probably more efficient
			$element = $this->params['plugin'] == ClassRegistry::init('Cpanel')->pluginName ? 'menu' : '../../plugins/' . ClassRegistry::init('Cpanel')->pluginName . '/views/elements/menu';
			
			$output .= $this->Tree->generate($sections, array('element' => $element));
			
			return $output;
		}
		
		function _adminMenuTools() {
			// debug(ClassRegistry::getObject('Cpanel')->listMenuSectionsRoute);exit;
			$output = '';
			if (Configure::read('debug')) {
				$output .= '<li>';
				$output .= $this->Html->link(__('Menu', true), ClassRegistry::getObject('Cpanel')->listMenuSectionsRoute);
				// $output .= '<ul>';
				// $output .= '<li>' . $this->Html->link(__('List Sections', true), ClassRegistry::getObject('Cpanel')->listMenuSectionsRoute) . '</li>';
				// $output .= '<li>' . $this->Html->link(__('Add Section', true), ClassRegistry::getObject('Cpanel')->newMenuSectionRoute) . '</li>';
				// $output .= '<li>' . $this->Html->link(__('Edit Sections', true), ClassRegistry::getObject('Cpanel')->editMenuSectionsRoute) . '</li>';
				// $output .= '</ul>';
				$output .= '</li>';
			}
			
			return $output;
		}
		
		// function _organize(&$items, $header) {
		// 	$output = '';
		// 	foreach ($items as &$item) {				
		// 		$route = unserialize($item['CpanelMenuItem']['match_route'])->route;
		// 		// Enforce items not to use cpanel plugin routes
		// 		$route['plugin'] = '';
		// 		if (!empty($item['children'])) {
		// 			$output .= "<h$header>" . $this->Html->link($item['CpanelMenuItem']['name'], $route) . "</h$header>\n" . $this->_organize($item['children'], $header + 1);
		// 		} else {
		// 			$output .= "<h$header>" . $this->Html->link($item['CpanelMenuItem']['name'], $route) . "</h$header>\n";
		// 		}
		// 	}
		// 	
		// 	return $output;
		// }
	}
	