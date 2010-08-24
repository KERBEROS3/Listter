
<?php if(!isset($login) || !$login){ ?>
		<?php
			if( isset($auto_login) ){
				$auto_login = "自動";
			}else{
				$auto_login = false;
			}
		?>
		<ul id="nav">
			<li><?php echo $html->link( $auto_login.'ログイン' , array('controller'=>'users','action'=>'login')); ?></li>
			<li><?php echo $html->link( 'ユーザー登録（無料）' , array('controller'=>'users','action'=>'add')); ?></li>
		</ul>
<?php }else{  ?>
		<ul id="nav">
			<li><?php echo $html->link( 'ホーム' , array('controller'=>'home')); ?></li>
			<li><?php echo $html->link( 'タイムライン' , array('controller' => 'tasks', 'action' => 'index','user_timeline' => TRUE,'url_user'=>'home','page'=>1)); ?></li>
			<li><?php echo $html->link( '設定' , array('controller'=>'users','action'=>'settings')); ?></li>
			<li><?php echo $html->link( 'ログアウト' , array('controller'=>'users','action'=>'logout')); ?></li>
		</ul>
<?php }  ?>