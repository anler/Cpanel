<?php echo $form->create('CpanelUser', array('url' => array('controller' => 'users', 'action' => 'password_forgotten'))) ?>
	
	<?php echo $form->input('CpanelUser.username', array('label' => __('Username:', true))) ?>
	
<?php echo $form->end(__('Submit', true)) ?>