<?php echo $form->create('User', array('url' => array('controller' => 'users', 'action' => 'login'))) ?>
	<fieldset id="" >
		<legend>Login</legend>
		<div id="validation-errors">
			<?php echo $form->error('User.username') ?>
			<?php echo $form->error('User.password') ?>
		</div>
		
		<?php echo $form->input('User.username', array('label' => __('Username:', true), 'id' => 'cpanelUsername')) ?>
		<?php echo $form->input('User.password', array('label' => __('Password:', true), 'id' => 'cpanelPassword')) ?>
	</fieldset>	
<?php echo $form->end(__('Login', true)) ?>