<div class="follows index">

<?php
//paginator用に各種urlの値を引き継ぐ
if($vars['type'] === 'following'){
	$url = array('url' => array('controller' => 'follows', 'action' => 'follow_list','following'=>TRUE));
	if(isset($vars['url_username'])){ $url['url']['url_user'] = $vars['url_username']; }
	$paginator->options($url);
}elseif($vars['type'] === 'followers'){
	$url = array('url' => array('controller' => 'follows', 'action' => 'follow_list','followers'=>TRUE));
	if(isset($vars['url_username'])){ $url['url']['url_user'] = $vars['url_username']; }
	$paginator->options($url);
}

// このページの説明を用意する。
$message = ($vars['type'] === 'following') ? "は{$stats['folloing']}人をフォローしています" : "は{$stats['follower']}人にフォローされています" ;
$name = isset($vars['url_username']) ? $vars['url_username'] : 'あなた' ;
//pr($message);
//pr($name);
?>

<h2><?php echo $name . $message ?></h2>

<p>
<?php
echo $paginator->counter(array(
'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
));
?></p>
<table cellpadding="0" cellspacing="0">


	<tr>
			<th class="name-header" >ユーザー名 / 名前</th>
			<!-- <th class="actions-header">操作</th> -->
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
			<?php
				echo $html->link($follow['User']['username'], array('controller'=>$follow['User']['username'])); 
				if(isset($follow['User']['realname'])){ 
					echo '<span class="realname">' . h($follow['User']['realname']) . '</span>';
				}
			?>
		</td>

		<!--
		<td class="actions">
			<?php echo $html->link(__('Delete', true), array('action'=>'delete', $follow['Follow']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $follow['Follow']['id'])); ?>
		</td>
		-->
	</tr>
<?php endforeach; ?>
</table>
</div>
<div class="paging">
	<?php echo $paginator->prev('<< '.__('previous', true), array(), null, array('class'=>'disabled'));?>
 | 	<?php echo $paginator->numbers();?>
	<?php echo $paginator->next(__('next', true).' >>', array(), null, array('class'=>'disabled'));?>
</div>
<!--
	<div class="actions">
		<ul>
			<li><?php echo $html->link(__('New Follow', true), array('action'=>'add')); ?></li>
		</ul>
	</div>
-->
