<div class="timelines view">
<h2><?php  __('Timeline');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $timeline['Timeline']['id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Task'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $html->link($timeline['Task']['id'], array('controller'=> 'tasks', 'action'=>'view', $timeline['Task']['id'])); ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Progress'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $timeline['Timeline']['progress']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Comment'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $timeline['Timeline']['comment']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Modified'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $timeline['Timeline']['modified']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('Edit Timeline', true), array('action'=>'edit', $timeline['Timeline']['id'])); ?> </li>
		<li><?php echo $html->link(__('Delete Timeline', true), array('action'=>'delete', $timeline['Timeline']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $timeline['Timeline']['id'])); ?> </li>
		<li><?php echo $html->link(__('List Timelines', true), array('action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New Timeline', true), array('action'=>'add')); ?> </li>
		<li><?php echo $html->link(__('List Tasks', true), array('controller'=> 'tasks', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New Task', true), array('controller'=> 'tasks', 'action'=>'add')); ?> </li>
	</ul>
</div>
