<?php
	/**
	* 
	*/
	class MenuController extends CpanelAppController {
		
		function index() {
			$this->set('sections', $this->CpanelMenu->find('threaded'));
		}
		
		function add() {
			// Redirect in production environment
			if (!Configure::read('debug')) {
				$this->redirect($this->referer());
			}
			
			if (!empty($this->data)) {
				if ($this->CpanelMenu->saveSection($this->data)) {
					$this->_redirectToIndex(__('Section saved.', true), $this->success);
				}
				
				$this->Session->setFlash(__('Section not saved. Check for validation errors.', true), $this->failure);
			}
			
			$this->set('items', $this->CpanelMenu->find('list'));
		}
		
		function moveup($id = null) {
			$this->_redirectIfInvalid($id);
			$this->_move($id, 'up');
		}

		function movedown($id = null) {
			$this->_redirectIfInvalid($id);
			$this->_move($id, 'down');
		}
		
		function edit($id = null) {
			empty($this->data) && $this->_redirectIfInvalid($id);
			
			if (!empty($this->data)) {
				if ($this->CpanelMenu->saveSection($this->data)) {
					$this->_redirectToIndex(__('Section saved.', true), $this->success);
				}
				
				$this->Session->setFlash(__('Section not saved. Check for validations errors.', true), $this->failure);
			} else {
				$this->data = $this->CpanelMenu->readSection($id);
			}
			
			$this->set('items', $this->CpanelMenu->find('list'));
		}
		
		function delete($id = null) {
			$this->_redirectIfInvalid($id);
			
			if ($this->CpanelMenu->delete($id)) {
				$this->_redirectToIndex(__('Section deleted', true), $this->success);
			}
			
			$this->_redirectToIndex(__('Section could not be deleted. Try again.', true), $this->failure);
		}
		
		
		// Private
		function _redirectToIndex($message = '', $layout = null) {
			if ($message) {
				$this->Session->setFlash($message, $layout);
			}
			$this->redirect(array('action' => 'index'));
		}
		function _redirectIfInvalid($id) {
			if (null === $id || !is_numeric($id)) {
				$this->Session->setFlash(__('Invalid id for section.', true), $this->notice);
				$this->redirect(array('action' => 'index'));
			}
			
			return;
		}
		
		function _move($id, $direction) {
			if ($this->CpanelMenu->move($id, $direction)) {
				$this->_redirectToIndex(__('Order changed', true), $this->success);
			}
			
			$this->_redirectToIndex(__('Order could not be changed. Try again', true), $this->failure);
		}
	}
	