<p class="credentials">
	username: <?php echo $username ?>
	<br />
	last login: <?php echo $time->niceShort($lastLogin) ?>
	<br />
	last login IP: <?php echo $lastLoginIP ?>
</p>

<br />

<?php echo $form->create('CpanelUser', array('url' => array('controller' => 'users', 'action' => 'account'))) ?>
	<fieldset>
		<legend><?php echo __('Change Password', true) ?></legend>
		<div id="validation-errors">
			<?php echo $form->error('currentpassword') ?>
			<?php echo $form->error('password') ?>
			<?php echo $form->error('repassword') ?>
		</div>
		<?php echo $form->hidden('CpanelUser.id', array('value' => $session->read('CpanelUser.id'))) ?>
		<?php echo $form->hidden('CpanelUser.username', array('value' => $session->read('CpanelUser.username'))) ?>
		
		<?php echo $form->input('CpanelUser.currentpassword', array('label' => __('Current Password', true), 'type' => 'password', 'error' => false)) ?>
		
		<?php echo $form->input('CpanelUser.password', array('label' => __('New Password', true), 'error' => false)) ?>
		
		<?php echo $form->input('CpanelUser.repassword', array('label' => __('Confirm New Password', true), 'type' => 'password', 'error' => false)) ?>
		
	</fieldset>
<?php echo $form->end(__('Save Changes', true)) ?>