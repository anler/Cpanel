<div class="menu-sections form">
<?php echo $form->create('CpanelMenuItem');?>
	<fieldset>
 		<legend><?php __('Edit Menu Sections');?></legend>
	<?php
		echo $form->input('id');
		echo $form->input('parent_id', array( 'type' => 'select', 'options' => $items, 'empty' => __('Root', true)));
		echo $form->input('name');
		echo $form->input('match_route');
	?>
	</fieldset>
<?php echo $form->end(__('Save Changes', true)) ?>
</div>
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('Delete', true), array('action' => 'delete', $form->value('CpanelMenuItem.id')), null, sprintf(__('Are you sure you want to delete # %s?', true), $form->value('CpanelMenuItem.name'))); ?></li>
	</ul>
</div>