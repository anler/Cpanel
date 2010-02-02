<?php echo $form->create('User', array('url' => array('controller' => 'users', 'action' => 'setup', Configure::read('Routing.admin') => true, 'plugin' => 'cpanel'))) ?>
	<fieldset id="" >
		<legend>Setup</legend>
		<div id="validation-errors">
			<?php echo $form->error('User.username') ?>
			<?php echo $form->error('User.password') ?>
			<?php echo $form->error('User.repassword') ?>
		</div>
		
		<?php echo $form->input('User.username', array('label' => __('Username:', true), 'id' => 'cpanelUsername', 'error' => false)) ?>
		<?php echo $form->input('User.password', array('label' => __('Password:', true), 'id' => 'cpanelPassword', 'error' => false)) ?>
		<?php echo $form->input('User.repassword', array('label' => __('Repeat Password', true), 'id' => 'cpanelRePassword', 'type' => 'password', 'error' => false)) ?>
	</fieldset>
<?php echo $form->end(__('Create Root Account', true)) ?>