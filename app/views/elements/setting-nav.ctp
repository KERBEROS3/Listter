<?php
	//pr($action);

	$id['password'] = null;
	$id['twitter'] = null;
	$id['notifications'] = null;
	$id['settings'] = null;
	
	//現在のaction表示とかの用意
	foreach($id as $k => $i){
		if($k === $action){ //action名と同じ配列ならidを設定する
			$id["$k"] = 'id="setting-now"';
		}
	}
?>


<ul id="setting-nav">
	<li <?php echo $id['settings'] ?> >
		<?php echo $html->link('ユーザー情報',array('controller' => 'users','action' => 'settings')); ?>
	</li>
	<li <?php echo $id['password'] ?> >
		<?php echo $html->link('パスワード',array('controller' => 'users','action' => 'password')); ?>
	</li>
	<li <?php echo $id['notifications'] ?> >
		<?php echo $html->link('お知らせ機能',array('controller' => 'users','action' => 'notifications')); ?>
	</li>
	<!-- <li id="setting-now"> -->
	<li <?php echo $id['twitter'] ?> class="twitter setting-last">
		<?php echo $html->link('Twitter',array('controller' => 'users','action' => 'twitter')); ?>
	</li>
<!--	
	<li ><a href="/account/notifications">お知らせ機能</a></li>
	<li ><a href="/account/picture">アイコン</a></li>
	<li ><a href="/account/profile_settings">デザイン</a></li>
	<li id="setting-last"><a href="/account/connections">このユーザーに対する操作</a></li>
-->	
	
</ul>
<div class="tab settings"> </div>