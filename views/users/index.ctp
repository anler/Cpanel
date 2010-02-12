<?php echo $html->link(__('Register New User', true), array('controller' => 'users', 'action' => 'register_user'), array('class' => 'button')) ?>
<table>
	<thead>
		<tr>
			<th><?php __('Username') ?></th>
			<th><?php __('Email') ?></th>
			<th><?php __('Last Login') ?></th>
			<th><?php __('Last Login IP') ?></th>
			<th><?php __('Actions', true) ?></th>
		</tr>
	</thead>
	
	<tbody>
		<?php foreach ($users as $user): ?>
			<tr>
				<td><?php echo $user['CpanelUser']['username'] ?></td>
				<td><?php echo $user['CpanelUser']['email'] ?></td>
				<td><?php echo $time->nice($user['CpanelUser']['last_login']) ?></td>
				<td><?php echo $user['CpanelUser']['last_login_ip'] ?></td>
				
				<td class="actions">
					<?php echo $html->link(__('Delete', true), array('action' => 'delete', $user['CpanelUser']['id']), array('class' => 'action-button')) ?>
				</td>
			</tr>
		<?php endforeach ?>
	</tbody>
</table>