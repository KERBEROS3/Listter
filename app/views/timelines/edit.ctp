<div class="timelines form">
<?php echo $form->create('Timeline');?>
	<fieldset>
 		<legend><?php __('Edit Timeline');?></legend>
	<?php
		echo $form->input('id');
		echo $form->input('task_id');
		echo $form->input('progress');
		echo $form->input('comment');
	?>
	</fieldset>
<?php echo $form->end('Submit');?>
</div>
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('Delete', true), array('action'=>'delete', $form->value('Timeline.id')), null, sprintf(__('Are you sure you want to delete # %s?', true), $form->value('Timeline.id'))); ?></li>
		<li><?php echo $html->link(__('List Timelines', true), array('action'=>'index'));?></li>
		<li><?php echo $html->link(__('List Tasks', true), array('controller'=> 'tasks', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New Task', true), array('controller'=> 'tasks', 'action'=>'add')); ?> </li>
	</ul>
</div>
