<div class="data"><?php echo $data['CpanelMenu']['name'] ?></div>

<div class="actions">
	<?php
		if (!$firstChild) {
			echo $html->link('↑', array('action' => 'moveup', $data['CpanelMenu']['id']), array('title' => __('Move Up', true)));
		}
		
		if (!$lastChild) {
			echo $html->link('↓', array('action' => 'movedown', $data['CpanelMenu']['id']), array('title' => __('Move Down', true)));
		}
		
		echo $html->link(__('edit', true), array('action' => 'edit', $data['CpanelMenu']['id']));
		echo $html->link(__('delete', true), array('action' => 'delete', $data['CpanelMenu']['id']), array('class' => 'delete'), sprintf(__('Are you sure you want to delete # %s?', true), $data['CpanelMenu']['id']));
	?>
</div>