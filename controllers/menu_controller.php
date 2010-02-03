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
				if ($this->CpanelMenu->saveSection($this->data)) {
					$this->Session->setFlash(__('Item saved.', true));
				} else {
					$this->Session->setFlash(__('Item not saved.', true));
				}
				
				unset($this->data['CpanelMenu']);
			}
			
			$this->set('items', $this->CpanelMenu->find('list'));
		}
		
		function edit($id = null) {
			if (null === $id) {
				$this->Session->setFlash(__('Invalid id for section.', true), 'messages/notice');
				$this->redirect($this->referer());
			}
			
			if (!empty($this->data)) {
				if ($this->CpanelMenu->saveSection($this->data)) {
					$this->Session->setFlash(__('Changes saved.', true));
					$this->redirect(ClassRegistry::init('Cpanel')->listMenuSectionsRoute);
				}
				
				$this->Session->setFlash(__('Changes not saved. Check validations errors.', true), 'messages/failure');
			} else {
				$this->data = $this->CpanelMenu->readSection($id);
			}
			
			$this->set('items', $this->CpanelMenu->find('list'));
		}
		
		function delete($id = null) {
			if (null === $id) {
				$this->Session->setFlash(__('Invalid id for section.', true));
				$this->redirect($this->referer());
			}
			
			if ($this->CpanelMenu->delete($id)) {
				$this->Session->setFlash(__('Section deleted.', true));
			} else {
				$this->Session->setFlash(__('Section could not be deleted. Try again.', true));
			}
			
			$this->redirect(ClassRegistry::init('Cpanel')->listMenuSectionsRoute);
			
		}
	}
	