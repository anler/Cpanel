<?php
	/**
	* 
	*/
	class ControlPanelController extends CpanelAppController {
		
		function beforeRender() {
			$this->_setCrumbs();
		}
		
		function dashboard() {}
		
		
		
		// Private
		function _setCrumbs() {
			// $this->Session->write('Cpanel.crumbs', $this->params)
		}
	}