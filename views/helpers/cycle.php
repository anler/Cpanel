<?php 
	/**
	* @todo Retornar clase dada una condición
	*/
	class CycleHelper extends AppHelper
	{
		var $_cycle = null;
		
		function __construct() {
			parent::__construct();
			$this->_cycle = new Cycle();
		}
		
		function even($class, $as_text = false) {
			return $this->_cycle->even($class, $as_text);
		}
		
		function isEven($num) {
			return $num % 2;
		}
		
		function isOdd($num) {
			return !$this->isEven($num);
		}
		
		function odd($class, $as_text = false) {
			return $this->_cycle->odd($class, $as_text);
		}
		
		function alternate() {
			$params = func_get_args();
			if (is_array($params[0])) {
				$params = $params[0];
			}
			
			return $this->_cycle->alternate($params);
		}
		
		function reset() {
			return $this->_cycle->reset();
		}
		
		function __get($label) {
			return $this->{$label} = new Cycle();
		}
	}
	
	/**
	* 
	*/
	class Cycle
	{
		private $_counter = null;
		
		public function __construct($count = 2) {
			$this->_counter = new CircularCounter($count);
		}
		
		public function even($class, $as_text) {
			return $this->_getOneOfTwo(
				array(
					'even' => $class
				),
				$as_text
			);
		}
		
		public function odd($class, $as_text) {
			return $this->_getOneOfTwo(
				array(
					'odd' => $class
				),
				$as_text
			);
		}
		
		public function alternate($classes) {
			if (is_array($classes)) {
				$count = count($classes);
			} else {
				$count = func_num_args();
				$classes = func_get_args();
			}
			
			$this->_counter->setTo($count);
				
			$current = $classes[$this->_counter->current()];
			
			return "class=\"$current\"";
		}
		
		public function reset() {
			$this->counter &&
				$this->_counter->reset();

			return $this;
		}
		
		function _getOneOfTwo($options = array(), $as_text) {
			$this->_counter->setTo(2);
			$current = $this->_counter->current();

			if ($class = @$options['even']) {
				if (!$current % 2) {
					$class = $as_text ? $class : "class=\"$class\"";
				} else {
					$class = '';
				}
								
			} elseif ($class = @$options['odd']) {
				if ($current % 2) {
					$class = $as_text ? $class : "class=\"$class\"";
				} else {
					$class = '';
				}
			} else {
				$class = '';
			}
			
			return $class;
		}
	}
	
	
	
	/**
	* 
	*/
	class CircularCounter
	{
		private $_cycles = 0;
		
		private $_current = 0;
		
		public function __construct($cycles = 2) {
			$this->_cycles = $cycles;
		}
		
		public function setTo($value) {
			if (is_numeric($value) && $value >= 0) {
				$this->_cycles = $value;
			}
			
			return $this;
		}
		
		public function get() {
			return $this->_cycles;
		}
		
		public function current() {
			return ($this->_current++) % $this->_cycles;
		}
		
		public function reset() {
			$this->_current = 0;
		}
	}