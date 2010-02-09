<?php echo $form->create('CpanelMenu', array('url' => array('controller' => 'menu'))) ?>
	<fieldset>
		<legend>Help</legend>
		
		Route - :controller => some_controller, :action => some_action, param1, named2:param2, ...
		
	</fieldset>
	
	<br />
	
	<fieldset id="new_cpanel_section">
		<legend>New Cpanel Section</legend>
		
		<?php echo $form->input('parent_id', array('label' => __('Parent Item', true), 'type' => 'select', 'options' => $items, 'empty' => __('Root Item', true))) ?>
		<?php echo $form->input('name') ?>
		<?php echo $form->input('match_route', array('type' => 'text')) ?>
		
	</fieldset>
<?php echo $form->end(__('Save it', true)) ?>