<div class="supports form">
<?php echo $form->create('Support');?>
	<fieldset>
 		<legend><?php __('Edit Support');?></legend>
	<?php
		echo $form->input('id');
		echo $form->input('task_id');
		echo $form->input('supporter_user_id');
		echo $form->input('points');
		echo $form->input('comment');
	?>
	</fieldset>
<?php echo $form->end('Submit');?>
</div>
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('Delete', true), array('action'=>'delete', $form->value('Support.id')), null, sprintf(__('Are you sure you want to delete # %s?', true), $form->value('Support.id'))); ?></li>
		<li><?php echo $html->link(__('List Supports', true), array('action'=>'index'));?></li>
	</ul>
</div>
