<div class="pieces form">
<?php echo $form->create('Piece');?>
	<fieldset>
 		<legend><?php __('Add Piece');?></legend>
	<?php
		echo $form->input('task_id');
		echo $form->input('comment');
	?>
	</fieldset>
<?php echo $form->end('Submit');?>
</div>
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('List Pieces', true), array('action' => 'index'));?></li>
		<li><?php echo $html->link(__('List Tasks', true), array('controller' => 'tasks', 'action' => 'index')); ?> </li>
		<li><?php echo $html->link(__('New Task', true), array('controller' => 'tasks', 'action' => 'add')); ?> </li>
	</ul>
</div>
