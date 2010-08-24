<div class="pieces index">
<h2><?php __('Pieces');?></h2>
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
	<th><?php echo $paginator->sort('comment');?></th>
	<th><?php echo $paginator->sort('modified');?></th>
	<th class="actions"><?php __('Actions');?></th>
</tr>
<?php
$i = 0;
foreach ($pieces as $piece):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>
		<td>
			<?php echo $piece['Piece']['id']; ?>
		</td>
		<td>
			<?php echo $html->link($piece['Task']['id'], array('controller' => 'tasks', 'action' => 'view', $piece['Task']['id'])); ?>
		</td>
		<td>
			<?php echo $piece['Piece']['comment']; ?>
		</td>
		<td>
			<?php echo $piece['Piece']['modified']; ?>
		</td>
		<td class="actions">
			<?php echo $html->link(__('View', true), array('action' => 'view', $piece['Piece']['id'])); ?>
			<?php echo $html->link(__('Edit', true), array('action' => 'edit', $piece['Piece']['id'])); ?>
			<?php echo $html->link(__('Delete', true), array('action' => 'delete', $piece['Piece']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $piece['Piece']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
</table>
</div>
<div class="paging">
	<?php echo $paginator->prev('<< '.__('previous', true), array(), null, array('class'=>'disabled'));?>
 | 	<?php echo $paginator->numbers();?>
	<?php echo $paginator->next(__('next', true).' >>', array(), null, array('class' => 'disabled'));?>
</div>
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('New Piece', true), array('action' => 'add')); ?></li>
		<li><?php echo $html->link(__('List Tasks', true), array('controller' => 'tasks', 'action' => 'index')); ?> </li>
		<li><?php echo $html->link(__('New Task', true), array('controller' => 'tasks', 'action' => 'add')); ?> </li>
	</ul>
</div>
