<?php echo $form->create('CpanelUser', array('url' => array('controller' => 'users', 'action' => 'register_user'))) ?>
	<fieldset>
		<legend><?php __('User Credentials', true) ?></legend>
		
		<?php echo $form->input('CpanelUser.username', array('label' => __('Username:', true))) ?>
		<?php echo $form->input('CpanelUser.email', array('label' => __('Email:', true))) ?>
	</fieldset>
<?php echo $form->end(__('Register New User', true)) ?>