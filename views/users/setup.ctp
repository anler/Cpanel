<?php echo $form->create('CpanelUser', array('url' => array('controller' => 'users', 'action' => 'register_user'))) ?>
	<fieldset>
		<legend><?php __('Root Account Setup', true) ?></legend>
		
		<?php echo $form->input('CpanelUser.username', array('label' => __('Username:', true), 'id' => 'cpanelUsername')) ?>
		
		<?php echo $form->input('CpanelUser.email', array('label' => __('Email', true), 'id' => 'cpanelEmail')) ?>
		
		<?php echo $form->input('CpanelUser.password', array('label' => __('Password:', true), 'id' => 'cpanelPassword')) ?>
		
		<?php echo $form->input('CpanelUser.repassword', array('label' => __('Confirm Password', true), 'id' => 'cpanelRePassword', 'type' => 'password')) ?>
	</fieldset>
<?php echo $form->end(__('Create Root Account', true)) ?>