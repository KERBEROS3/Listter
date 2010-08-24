
<?php
//リンク用のurlを用意

$followers = array("controller" => 'follows','action'=>'follow_list','followers'=>TRUE,'page'=>1);
$following = array("controller" => 'follows','action'=>'follow_list','following'=>TRUE,'page'=>1);
$updates = array('controller' => 'tasks', 'action' => 'index','user_timeline' => TRUE,'url_user'=>'home','page'=>1);

if( !isset($login['User']['username']) || $login['User']['username'] !== $url_user || (isset($vars['type'])&&!isset($vars['url_user'])) ){
	//pr($followers);
	//pr($following);
	//pr($page_user['User']['username']);
	if(isset($url_user)){
		$followers['url_user'] = $url_user;
		$following['url_user'] = $url_user;
		$updates['url_user'] = $page_user['User']['username'];
	}
}

?>

<div class="stats">
	<table><tbody><tr>
		<td>
			<a title="See who you’re following" rel="me" class="link-following_page" id="following_count_link" href="<?php echo $html->url($following); ?>">
				<span class="stats_count numeric" id="following_count"> <?php echo $stats['folloing'] ?> </span>
				<span class="label">フォロー<br/>している</span>
			</a>
		</td>
		
		<td>
			<a title="See who’s following you" rel="me" class="link-followers_page" id="follower_count_link" href="<?php echo $html->url($followers); ?>">
				<span class="stats_count numeric" id="follower_count"> <?php echo $stats['follower'] ?> </span>
				<span class="label">フォロー<br/>されている</span>
			</a>
		</td>

		<td>
			<a rel="me" title="See all your updates" class="link-updates" href="<?php echo $html->url($updates); ?>">
			<span class="stats_count numeric" id="update_count"> <?php echo $stats['timeline_count'] ?> </span>
			<span class="label">ねがいの更新数</span>
			</a>
		</td>

	</tr></tbody></table>
</div>