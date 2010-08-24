<div class="pieces view">
<h2><?php  __('Piece');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $piece['Piece']['id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Task'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $html->link($piece['Task']['id'], array('controller' => 'tasks', 'action' => 'view', $piece['Task']['id'])); ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Comment'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $piece['Piece']['comment']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Modified'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $piece['Piece']['modified']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('Edit Piece', true), array('action' => 'edit', $piece['Piece']['id'])); ?> </li>
		<li><?php echo $html->link(__('Delete Piece', true), array('action' => 'delete', $piece['Piece']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $piece['Piece']['id'])); ?> </li>
		<li><?php echo $html->link(__('List Pieces', true), array('action' => 'index')); ?> </li>
		<li><?php echo $html->link(__('New Piece', true), array('action' => 'add')); ?> </li>
		<li><?php echo $html->link(__('List Tasks', true), array('controller' => 'tasks', 'action' => 'index')); ?> </li>
		<li><?php echo $html->link(__('New Task', true), array('controller' => 'tasks', 'action' => 'add')); ?> </li>
	</ul>
</div>
