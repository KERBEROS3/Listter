<div class="timelines index">
<h2><?php __('Timelines');?></h2>
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
	<th><?php echo $paginator->sort('progress');?></th>
	<th><?php echo $paginator->sort('comment');?></th>
	<th><?php echo $paginator->sort('modified');?></th>
	<th class="actions"><?php __('Actions');?></th>
</tr>
<?php
$i = 0;
foreach ($timelines as $timeline):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>
		<td>
			<?php echo $timeline['Timeline']['id']; ?>
		</td>
		<td>
			<?php echo $html->link($timeline['Task']['task'], array('controller'=> 'tasks', 'action'=>'view', $timeline['Task']['id'])); ?>
		</td>
		<td>
			<?php echo $timeline['Timeline']['progress']; ?>
		</td>
		<td>
			<?php echo $timeline['Timeline']['comment']; ?>
		</td>
		<td>
			<?php echo $timeline['Timeline']['modified']; ?>
		</td>
		<td class="actions">
			<?php echo $html->link(__('View', true), array('action'=>'view', $timeline['Timeline']['id'])); ?>
			<?php echo $html->link(__('Edit', true), array('action'=>'edit', $timeline['Timeline']['id'])); ?>
			<?php echo $html->link(__('Delete', true), array('action'=>'delete', $timeline['Timeline']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $timeline['Timeline']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
</table>
</div>



<!-- //新しい表示 -->
<div class="list">
<ol>

<?php
//var_dump($timelines);
$i = 0;
foreach ($timelines as $timeline):
?>	
	
	<li <?php if($i++ === 0){echo 'class="first_list"';}?> >
	<dl>
		<dt>id</dt>
		<dd class="id">
			<?php echo $timeline['Timeline']['id']; ?>
		</dd>
		<dt>リスト</dt>
		<dd class="dream">
			<?php echo $html->link($timeline['Task']['task'], array(
																	'controller'=> 'timelines',
																	'action'=>'add', 
																	$timeline['Timeline']['id']
																)
									);
			?>
		</dd>
		<dt>リストの進捗率</dt>
		<dd class="progress">
			<?php echo isset($timeline['Timeline']['progress']) ? $timeline['Timeline']['progress'] : '0' ; ?><span class="percent">%</span>
		</dd>
		<dt>現在のリストの状況</dt>
		<dd class="comment">
			<?php echo isset($timeline['Timeline']['comment']) ? $timeline['Timeline']['comment'] : null ; ?>
		</dd>
		<dt>夢開始からの経過時間</dt>
		<dd class="created_elapsed">
			<?php echo $timeline['Timeline']['created_elapsed']; ?>
			<?php //echo $timeline['Timeline']['created']; ?>
		</dd>
		<dt>前回の夢更新からの経過時間</dt>
		<dd class="modified_elapsed">
			<?php echo $timeline['Timeline']['modified_elapsed']; ?>
			<?php //echo $timeline['Timeline']['modified']; ?>
			<?php //echo date('Y-m-d D H:i:s',strtotime($timeline['Timeline']['modified'])); ?>
		</dd>
		<dt>操作</dt>
		<dd class="actions">
			<?php echo $html->link(__('View', true), array('action'=>'view', $timeline['Timeline']['id'])); ?>
			<?php //echo $html->link(__('Edit', true), array('action'=>'edit', $timeline['Timeline']['id'])); ?>
			<?php echo $html->link(__('Delete', true), array('action'=>'delete', $timeline['Timeline']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $timeline['Timeline']['id'])); ?>
		</dd>
	</dl>
	</li>
<?php endforeach; ?>
</ol>
</div>
<!-- 新しい表示終了 -->



<div class="paging">
	<?php echo $paginator->prev('<< '.__('previous', true), array(), null, array('class'=>'disabled'));?>
 | 	<?php echo $paginator->numbers();?>
	<?php echo $paginator->next(__('next', true).' >>', array(), null, array('class'=>'disabled'));?>
</div>
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('New Timeline', true), array('action'=>'add')); ?></li>
		<li><?php echo $html->link(__('List Tasks', true), array('controller'=> 'tasks', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New Task', true), array('controller'=> 'tasks', 'action'=>'add')); ?> </li>
	</ul>
</div>
