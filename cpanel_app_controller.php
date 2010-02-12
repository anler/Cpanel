<?php
	/**
	* 
	*/
	class CpanelAppController extends AppController {
		var $components = array('Session');
		var $uses = array('Cpanel.CpanelMenu');
		var $helpers = array('Cpanel.Cpanel', 'Html', 'Form', 'Javascript', 'Cpanel.Tree');
		
		var $failure = 'flash/failure';
		var $notice = 'flash/notice';
		var $success = 'flash/success';
		
		// Private
		
		function _redirectToIndex($message = '', $layout = null) {
			if ($message) {
				$this->Session->setFlash($message, $layout);
			}
			$this->redirect(array('action' => 'index'));
		}
		
		function _redirectIfInvalid($id, $message = 'Invalid id') {
			if (null === $id || !is_numeric($id)) {
				$this->Session->setFlash($message, $this->notice);
				$this->redirect(array('action' => 'index'));
			}
			
			return;
		}
	}
?>