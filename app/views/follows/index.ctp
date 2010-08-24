<div class="follows index">
<h2><?php __('Follows');?></h2>
<p>
<?php
echo $paginator->counter(array(
'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
));
?></p>
<table cellpadding="0" cellspacing="0">
<tr>
	<th><?php echo $paginator->sort('id','Follow.id');?></th>
	<th><?php echo $paginator->sort('user_id','Follow.user_id');?></th>
	<th><?php echo $paginator->sort('created','Follow.created');?></th>
	<th><?php echo $paginator->sort('modified','Follow.modified');?></th>
	<th><?php echo $paginator->sort('follow_user_id','Follow.follow_user_id');?></th>
	<th class="actions"><?php __('Actions');?></th>
</tr>
<?php
$i = 0;
foreach ($follows as $follow):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>
		<td>
			<?php echo $follow['Follow']['id']; ?>
		</td>
		<td>
			<?php echo $follow['Follow']['user_id']; ?>
		</td>
		<td>
			<?php echo $follow['Follow']['created']; ?>
		</td>
		<td>
			<?php echo $follow['Follow']['modified']; ?>
		</td>
		<td>
			<?php echo $follow['Follow']['follow_user_id']; ?>
		</td>
		<td class="actions">
			<?php echo $html->link(__('View', true), array('action'=>'view', $follow['Follow']['id'])); ?>
			<?php echo $html->link(__('Edit', true), array('action'=>'edit', $follow['Follow']['id'])); ?>
			<?php echo $html->link(__('Delete', true), array('action'=>'delete', $follow['Follow']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $follow['Follow']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
</table>
</div>
<div class="paging">
	<?php echo $paginator->prev('<< '.__('previous', true), array(), null, array('class'=>'disabled'));?>
 | 	<?php echo $paginator->numbers();?>
	<?php echo $paginator->next(__('next', true).' >>', array(), null, array('class'=>'disabled'));?>
</div>
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('New Follow', true), array('action'=>'add')); ?></li>
	</ul>
</div>
