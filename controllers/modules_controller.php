<?php
	/**
	* 
	*/
	class ModulesController extends CpanelAppController
	{
		
		function new_menu_item() {
			if (!Configure::read('debug')) {
				$this->redirect($this->referer());
			}
			
			if (!empty($this->data)) {
				if ($this->CpanelMenu->newSection($this->data)) {
					$this->Session->setFlash(__('Item saved.', true));
				} else {
					$this->Session->setFlash(__('Item not saved.', true));
				}
				
				// if ($this->CpanelMenuItem->newItem($this->data)) {
				// 	$this->Session->setFlash(__('Item saved.', true));
				// } else {
				// 	$this->Session->setFlash(__('Item not saved.', true));
				// }
			}
			
			$this->set('items', $this->CpanelMenu->find('list'));
		}
		
		function list_menu_sections() {
			// $this->set('sections', $this->CpanelMenuItem->findSections());
			$this->set('sections', $this->CpanelMenu->findSections());
		}
		
		function edit_menu_sections() {
			
		}
		
		function dashboard() {}
	}
	
?>