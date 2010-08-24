<div class="tasks view">

	<?php echo $this->renderElement('profile-head'); ?>

	<h2 id="task_title">
		<?php 
			echo "<span>{$task['Task']['task']}</span>" . __('のみちのり',true);
			if( isset($login) && $url_user === $login['User']['username'] ){
				echo $html->link($html->image("pencil.png", array("alt" => __('ねがいを更新',true),"title" => __('ねがいを更新',true))), array('controller'=> 'timelines','action'=>'add',$task['Task']['id']),false,false,false);
			}
		
		?>
	</h2>

	<dl>
		<dt><?php __('Created'); ?></dt>
		<dd>
			<?php echo $task['Task']['created']; ?>
			&nbsp;
		</dd>
		<dt><?php __('Modified'); ?></dt>
		<dd>
			<?php echo $task['Task']['modified']; ?>
			&nbsp;
		</dd>
	</dl>
	
</div>




<!-- //新しい表示 -->
<div class="list tasks_view">

<?php

if( count($task['Timeline']) > 1 ){
	//$paginator->options( array( 'model' => 'Timelines' ) );
	//pr($this->passedArgs);
	//$paginator->options( array( 'url' => array( $task['Task']['id'] ) ) );
	$paginator->options( array( 'url' => array('controller'=>'tasks','action'=>'view','url_user'=>$url_user,'task_id'=>$task['Task']['id']) ) );
	echo $paginator->counter(array(
			'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true
		)));
}
		
?>

<ol>

<?php
//pr($task);
$i = 0;
foreach ($task['Timeline'] as $timeline):

	//ログインしてて、url_userとログインユーザが同一。
	if( isset($login) && $url_user === $login['User']['username'] ){
		$css_delete = false;
	}else{
		$css_delete = array('class'=>'hidden');  //htmlヘルパーの第三要素に設定する
	}

?>	
	
	<li <?php if($i === 0){echo 'class="first_list"';}?> >
	<dl>
		<dt>id</dt>
		<dd class="id">
			<?php echo $timeline['Timeline']['id']; ?>
		</dd>
		<dt>ドリーム(願い)</dt>
		<dd class="dream">
			<?php echo h($task['Task']['task']) ?>
		</dd>
		<?php // /public_timlineであればユーザ名を表示する
		if( isset($url_params) && $url_params === 'public_timeline' ){ ?>
		<dt>ユーザ名</dt>
		<dd class="user_name">
			<?php echo $task['User']['username']; ?>
		</dd>
		<?php } ?>
		<dt>リストの進捗率</dt>
		<dd class="progress <?php if( isset($url_params) && $url_params === 'public_timeline' ){ echo ' ' . $url_params;} ?>">
			<?php echo isset($timeline['Timeline']['progress']) ? $timeline['Timeline']['progress'] : '0' ; ?><span class="percent">%</span>
		</dd>
		<dt>現在のリストの状況</dt>
		<dd class="comment">
			<?php //echo isset($timeline['Timeline']['comment']) ? h($timeline['Timeline']['comment']) : null ; ?>
			<?php echo $text->autoLinkUrls(h(mb_convert_kana($timeline['Timeline']['comment'],"s")),array('target'=>'_blank')); ?>
		</dd>
		<dt>夢開始からの経過時間</dt>
		<dd class="created_elapsed">
			<?php echo $task['Task']['created_elapsed']; ?>
			<?php //echo $task['Task']['created']; ?>
		</dd>
		<dt>前回の夢更新からの経過時間</dt>
		<dd class="modified_elapsed">
			<?php //echo $task['Timeline'][$i++]['Timeline']['modified_elapsed']; ?>
			<?php echo $html->link($task['Timeline'][$i]['Timeline']['modified_elapsed'],array('controller' => 'tasks','action' => 'view','url_user'=>$url_user,'timeline_id'=> $task['Timeline'][$i]['Timeline']['id'] ) , array('title'=>$task['Timeline'][$i++]['Timeline']['modified']) ); ?>
			<?php //echo $task['Task']['modified']; ?>
			<?php //echo date('Y-m-d D H:i:s',strtotime($task['Task']['modified'])); ?>
		</dd>
		<dt>操作</dt>
		<dd class="actions">
			<?php //echo $html->link(__('View', true), array('controller'=> 'timelines', 'action'=>'view', $timeline['Timeline']['id'])); ?>
			<?php //echo $html->link(__('Delete', true), array('controller'=> 'timelines','action'=>'delete', $timeline['Timeline']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $task['Task']['id'])); ?>
			<?php echo $html->link($html->image("eraser.png", array("alt" => "みちのりを削除" ,"title" => "みちのりを削除")),
									array('controller'=> 'timelines','action'=>'delete', $timeline['Timeline']['id']),
									$css_delete,
									//sprintf(__('[%s]\nAre you sure you want to delete?', true), $timeline['Timeline']['comment']),
									sprintf(__('この「みちのり」を本当に削除してよいですか?\n\n「%s」', true), $timeline['Timeline']['comment']),
									false
								); ?>
			<?php //echo $html->link(__('Update', true), array('controller'=> 'timelines','action'=>'add',$task['Task']['id'])); ?>
		</dd>
	</dl>
	</li>
<?php endforeach; ?>
</ol>
</div>

<div class="paging">
	<?php
		if( count($task['Timeline']) > 1 ){
			$options['last'] = 1;
			$options['first'] = 1;
			$options['separator'] = null;
			$options['modulus'] = 10;
			//echo $paginator->first('最初',array('after'=>'　'));
			echo $paginator->prev('<< '.__('previous', true), array(), null, array('class'=>'disabled'));
			//echo $paginator->numbers(array('separator'=>null));
			echo $paginator->numbers($options);
			echo $paginator->next(__('next', true).' >>', array(), null, array('class'=>'disabled'));
			//echo $paginator->last('最後',array('before'=>'　'));
		}
	?>
</div>

<!-- 新しい表示終了 -->
