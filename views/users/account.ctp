<h2><?php echo __('My Settings', true) ?></h2>

<br />

<p>
	Username: <?php echo $username ?>
</p>

<br />

<?php echo $form->create('CpanelUser', array('url' => array('action' => 'account'))) ?>
	<fieldset>
		<legend><?php echo __('Change Password', true) ?></legend>
		
		<?php echo $form->input('CpanelUser.currentpassword', array('label' => __('Current Password', true))) ?>
		
		<?php echo $form->input('CpanelUser.password', array('label' => __('New Password', true))) ?>
		
		<?php echo $form->input('CpanelUser.repassword', array('label' => __('Confirm New Password', true))) ?>
		
	</fieldset>
<?php echo $form->end(__('Save Changes', true)) ?>