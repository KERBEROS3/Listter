<div class="follows view">
<h2><?php  __('Follow');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $follow['Follow']['id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('User Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $follow['Follow']['user_id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Created'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $follow['Follow']['created']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Modified'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $follow['Follow']['modified']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Follow User Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $follow['Follow']['follow_user_id']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('Edit Follow', true), array('action'=>'edit', $follow['Follow']['id'])); ?> </li>
		<li><?php echo $html->link(__('Delete Follow', true), array('action'=>'delete', $follow['Follow']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $follow['Follow']['id'])); ?> </li>
		<li><?php echo $html->link(__('List Follows', true), array('action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New Follow', true), array('action'=>'add')); ?> </li>
	</ul>
</div>
