<?php echo $form->create('CpanelUser', array('url' => ClassRegistry::init('Cpanel')->loginRoute)) ?>
	<fieldset id="" >
		<legend>Login</legend>
		<div id="validation-errors">
			<?php echo $form->error('username') ?>
			<?php echo $form->error('password') ?>
		</div>
		
		<?php echo $form->input('username', array('label' => __('Username:', true), 'id' => 'cpanelUsername')) ?>
		<?php echo $form->input('password', array('label' => __('Password:', true), 'id' => 'cpanelPassword')) ?>
	</fieldset>	
<?php echo $form->end(__('Login', true)) ?>