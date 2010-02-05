<?php
	/**
	* 
	*/
	class CpanelMenu extends CpanelAppModel
	{
		var $useTable = 'cpanel_menu';
		
		var $validate = array(
			'name' => array('notempty'),
			//@todo Improve validation (ej: not white spaces)
			'match_route' => array('notempty')
		);
		
		var $actsAs = array('Tree');
		
		function move($id, $direction) {
			$success = false;
			switch ($direction) {
				case 'up':
					$success = $this->moveup($id, true);
					break;
				
				case 'down':
					$success = $this->movedown($id);
					break;
			}
			
			return $success;
		}
		
		function saveSection($data) {
			$this->set($data);
			$success = false;

			if ($success = $this->validates()) {
				$this->data[$this->name]['url'] = Inflector::underscore($this->data[$this->name]['name']);
				$this->data[$this->name]['match_route'] = MenuItemRoute::serializeRoute($this->data[$this->name]['match_route']);
				
				$succes = $this->save(null, false);
			}
			
			return $success;
		}
		
		function readSection($id) {
			$section = $this->read(array('id', 'parent_id', 'name', 'match_route'), $id);
			
			$section[$this->name]['match_route'] = MenuItemRoute::unserializeRoute($this->data[$this->name]['match_route']);
			
			return $section;
		}
		
		function findSections() {
			$sections = $this->find('threaded', array('fields' => array('id', 'parent_id', 'name', 'url', 'match_route')));

			return $sections;
		}
		
		function getSectionsTree() {
			$items = $this->find('threaded', array('fields' => array('id', 'parent_id', 'name', 'url', 'match_route')));
			
			return $items;
		}
		
		function getSectionBranch($section) {
			$section = $this->find('first', array(
				'conditions' => array('url' => $section),
				'fields' => array('id')
			));
			
			debug($section);exit;
			// return $this->
		}
	}
	
	
	/**
	* 
	*/
	class MenuItemRoute
	{
		static $_instance;
		
		public $route = array();
		
		private function __construct() {}
		private function __clone() {}
		
		public static function getInstance() {
			if (!(self::$_instance instanceof self)) {
				self::$_instance = new self();
			}
			
			return self::$_instance;
		}
		
		public static function cleanParam($params) {
			if (is_array($params)) {
				$param = trim($params[0], ':\t ');
				$value = trim($params[1]);
				
				$result = array('name' => $param, 'value' => $value);
			} else {
				$result = trim($params, '\t ');
			}
			
			return $result;
		}
		
		public static function serializeRoute($route) {
			$tokens = String::tokenize($route);
			
			foreach ($tokens as $token) {
				// There are three different cases
				// 1- :param => value
				// 2- pass => array('value1', 'value2')
				// 3- named => array('param1' => 'value1', 'param2' => 'value2')
				switch (true) {
					// Case 1 - :param => value
					case count($param = explode('=>', $token)) > 1:
						$param = self::cleanParam($param);
						self::getInstance()->route[$param['name']] = $param['value'];
						break;
					// Case 2 - named => array('param1' => 'value1', 'param2' => 'value2', ...)
					case count($param = explode(':', $token)) > 1:
						$param = self::cleanParam($param);
						self::getInstance()->route[] = $param['name'] . ':' . $param['value'];;
						break;
					// Case 3 - pass => array('value1', 'value2', ...)
					default:
						$param = self::cleanParam($token);
						self::getInstance()->route[] = $param;
						break;
				}
			}
			
			return serialize(self::getInstance());
		}
		
		public static function unserializeRoute($route, $asArray = false) {
			$Route = unserialize($route);
			
			if ($asArray) {
				return $Route->route;
			}
			
			$routes = array();
			
			foreach ($Route->route as $key => $value) {
				switch (true) {
					case is_numeric($key):
						$routes[] = $value;
						break;
						
					// @todo Extends array('controller',...) to support customs params specified
					// 		 in routes.php config file
					case in_array($key, array('controller', 'action', 'plugin')):
						$routes[] = ":$key => $value";
						break;
					
					case is_string($key):
						$routes[] = "$key:$value";
						break;
				}
			}

			return trim(implode(', ', $routes), ', ');
		}
	}
	