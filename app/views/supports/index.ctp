<div class="supports index">
<h2><?php __('Supports');?></h2>
<p>
<?php
echo $paginator->counter(array(
'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
));
?></p>
<table cellpadding="0" cellspacing="0">
<tr>
	<th><?php echo $paginator->sort('id');?></th>
	<th><?php echo $paginator->sort('task_id');?></th>
	<th><?php echo $paginator->sort('supporter_user_id');?></th>
	<th><?php echo $paginator->sort('points');?></th>
	<th><?php echo $paginator->sort('comment');?></th>
	<th><?php echo $paginator->sort('created');?></th>
	<th><?php echo $paginator->sort('modified');?></th>
	<th class="actions"><?php __('Actions');?></th>
</tr>
<?php
$i = 0;
foreach ($supports as $support):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>
		<td>
			<?php echo $support['Support']['id']; ?>
		</td>
		<td>
			<?php echo $support['Support']['task_id']; ?>
		</td>
		<td>
			<?php echo $support['Support']['supporter_user_id']; ?>
		</td>
		<td>
			<?php echo $support['Support']['points']; ?>
		</td>
		<td>
			<?php echo $support['Support']['comment']; ?>
		</td>
		<td>
			<?php echo $support['Support']['created']; ?>
		</td>
		<td>
			<?php echo $support['Support']['modified']; ?>
		</td>
		<td class="actions">
			<?php echo $html->link(__('View', true), array('action'=>'view', $support['Support']['id'])); ?>
			<?php echo $html->link(__('Edit', true), array('action'=>'edit', $support['Support']['id'])); ?>
			<?php echo $html->link(__('Delete', true), array('action'=>'delete', $support['Support']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $support['Support']['id'])); ?>
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
		<li><?php echo $html->link(__('New Support', true), array('action'=>'add')); ?></li>
	</ul>
</div>
