<?php echo $form->create('CpanelUser', array('url' => array('controller' => 'users', 'action' => 'setup'))) ?>
	<fieldset>
		<legend>Setup</legend>
		<div id="validation-errors">
			<?php echo $form->error('username') ?>
			<?php echo $form->error('password') ?>
			<?php echo $form->error('repassword') ?>
		</div>
		
		<?php echo $form->input('username', array('label' => __('Username:', true), 'id' => 'cpanelUsername', 'error' => false)) ?>
		<?php echo $form->input('password', array('label' => __('Password:', true), 'id' => 'cpanelPassword', 'error' => false)) ?>
		<?php echo $form->input('repassword', array('label' => __('Repeat Password', true), 'id' => 'cpanelRePassword', 'type' => 'password', 'error' => false)) ?>
	</fieldset>
<?php echo $form->end(__('Create Root Account', true)) ?>