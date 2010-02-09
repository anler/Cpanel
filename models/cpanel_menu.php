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
				$this->data[$this->name]['url'] = Inflector::slug(low($this->data[$this->name]['name']), '-');
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
		
		function findSectionPath($id = null) {
			if ($id !== null) {
				$parents = $this->getpath($id);
				
				$conditions = array(
					'or' => array(
						array('parent_id' => null),
						array('parent_id' => $id)
					)
				);
				
				foreach ($parents as $parent) {
					$conditions['or'][] = array('parent_id' => $parent[$this->name]['parent_id']);
				}
			} else {
				$conditions = array('parent_id' => null);
			}
			
			return $this->find('threaded', array('conditions' => $conditions));
		}
		
		function findTree() {
			return $this->find('threaded');
		}
		
		/**
		 * 
		 */
		function findSectionFromUrl($url, $options = array()) {
			$options = Set::merge(
				array('conditions' => array('url' => $url)),
				$options
			);
			
			return $this->find('first', $options);
		}
		
		/**
		 * 
		 */
		function findSectionPathFromUrl($url, $options = array()) {
			$section = $this->findSectionFromUrl($url, $options);
			
			if (!empty($section)) {
				$path = $this->getpath($section[$this->name]['id']);
				$section['path'] = $path;
			}
			
			return $section;
		}
		
		function findSectionChildren($url) {
			$node = $this->_findSection($url);
			
			return $this->find('all', array('conditions' => array('parent_id' => $node[$this->name]['id'])));
		}
		
		
		// Private
		/**
		 * 
		 */
		function _hasChildren($node) {
			$children = $this->find('first', array(
				'condition' => array('parent_id' => $node[$this->name]['id'])
			));
			
			$empty = empty($children) ? true : false;
			
			return !$empty;
		}
		
		/**
		 * 
		 */
		function _findSection($url) {
			isset($this->__section) || $this->__section = $this->findByUrl($url);
			
			return $this->__section;
		}
	}
	
	
	/**
	* 
	*/
	class MenuItemRoute {
		/**
		 * 
		 */
		static $_instance;
		
		/**
		 * 
		 */
		public $route = array();
		
		private function __construct() {}
		private function __clone() {}
		
		/**
		 * 
		 */
		public static function getInstance() {
			if (!(self::$_instance instanceof self)) {
				self::$_instance = new self();
			}
			
			return self::$_instance;
		}
		
		/**
		 * 
		 */
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
		
		/**
		 * 
		 */
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
		
		/**
		 * 
		 */
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
	