<?php
	/**
	* 
	*/
	class MenuController extends CpanelAppController {
		
		function index() {
			$this->set('sections', $this->CpanelMenu->findSections());
		}
		
		function add() {
			// Redirect in production environment
			if (!Configure::read('debug')) {
				$this->redirect($this->referer());
			}
			
			if (!empty($this->data)) {
				if ($this->CpanelMenu->newSection($this->data)) {
					$this->Session->setFlash(__('Item saved.', true));
				} else {
					$this->Session->setFlash(__('Item not saved.', true));
				}
				
				unset($this->data['CpanelMenu']);
			}
			
			$this->set('items', $this->CpanelMenu->find('list'));
		}
		
		function edit($id = null) {
			// code...
		}
		
		function delete($id = null) {
			// code...
		}
	}
	