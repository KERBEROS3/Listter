<div class="follows form">
<?php echo $form->create('Follow');?>
	<fieldset>
 		<legend><?php __('Edit Follow');?></legend>
	<?php
		echo $form->input('id');
		echo $form->input('user_id');
		echo $form->input('follow_user_id');
	?>
	</fieldset>
<?php echo $form->end('Submit');?>
</div>
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('Delete', true), array('action'=>'delete', $form->value('Follow.id')), null, sprintf(__('Are you sure you want to delete # %s?', true), $form->value('Follow.id'))); ?></li>
		<li><?php echo $html->link(__('List Follows', true), array('action'=>'index'));?></li>
	</ul>
</div>
