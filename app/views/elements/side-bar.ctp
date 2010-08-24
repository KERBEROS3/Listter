<?php 
	//@pr('$url_params: '.$url_params);
	//@pr('$url_user_home: '.$url_user_home);
	//@pr('$url_user: '.$url_user);
	//@pr('$action: '.$action); //setting関連の場合定義
	//@pr('$login[User][username]: ' .$login['User']['username']);
	//@pr($login); //setting関連の場合定義 ユーザ情報にパスワード等あるので本番環境では表示禁止!(twitter関連のみなので何とかする予定)
	//@pr('$page_user[User][username]: ' .$page_user['User']['username']);
	//@pr($page_user);
	

	// css の id表示用の配列をliの数分だけ用意
	$id = array('home'=>false,'timeline'=>false,'user_task'=>false,'user_timeline'=>false,'public_timeline'=>false,'tasks/view'=>false);
	if(isset($url_user_home) && $url_user_home === TRUE){ //ユーザのホーム(timline含む)の場合
		//var_dump($url_user_home);
		
		if($url_params === 'home'){
			$id['home'] = 'active'; //ホームを強調
		}elseif($url_params === 'home/timeline'){
			$id['timeline'] = 'active'; //タイムラインを強調
		}
		
	}elseif( isset($url_params) && isset($url_user) && low($url_params) === $url_user){  //url_userと表示しているユーザが一緒なら
		$id['user_task'] = 'active'; //{$url_user}のねがいを強調
	}elseif(isset($url_params) && isset($url_user) && $url_params === $url_user . '/timeline' ){
		$id['user_timeline'] = 'active'; //{$url_user}のタイムラインを強調
	}elseif(isset($url_params) && $url_params === 'public_timeline'){
		$id['public_timeline'] = 'active';
	}elseif(isset($url_params) && $url_params === 'tasks/view'){
		if(isset($timeline_id)){
			$id['mitinori'] = 'active';
		}else{
			$id['tasks/view'] = 'active';
		}
		
	}elseif(isset($url_params) && $url_params === '/'){          //トップページだったら
		$id['public_timeline'] = 'top_page';
	}
	
?>

<div id="sidecontent">

	<?php
		//トップページだったら
		if(isset($url_params) && $url_params === '/'){
			echo '<div>';
			echo $form->create('User', array('action' => 'login'));
			echo '<fieldset>';
			echo "<legend>" . __('ここからログイン',true) . "</legend>";
			echo $form->input('username',array('label' => __('ユーザ名',true)));
			echo $form->input('password',array('label' => __('パスワード',true)));
			//echo $form->input('auto_login', array('type' => 'checkbox', 'label' => 'Log me in automatically?'));
			echo $form->checkbox('remember_me').'<label for="UserRememberMe">次回から自動的にログイン</label>';
			echo $form->end( __('ログイン',true) );
	?>
			<p class="forgot">
				パスワードを忘れた？<br/><?php echo $html->link("ここをクリック",array('controller' => 'users','action' => 'recent_password')); ?>.
			</p>
	<?php
			echo '</fieldset>';
			
			
			echo '</div>';
		}
	
	?>

	<ul>
	<?php
	// /loginとか用にひとまず設置
	// isset($vars['type'])　は、つまり /followers または //following ではない場合
	if(isset($url_user) || isset($vars['type']) ){
	?>
		<?php //他ユーザページの場合に先に、ユーザのタイムラインを出す
		if( !isset($url_params) 
			|| (
				$url_params !== 'public_timeline' 
				&& $url_params !== 'home' 
				&& $url_params !== 'home/timeline' 
				&& !( isset($login) && $url_params === 'tasks/view' && $url_user === $login['User']['username'])
			   ) 
		){
		?>
			<ul class="about vcard entry-author">
				<li><span class="label">名前</span>
					<span class="fn">
					<?php echo $page_user['User']['realname'] ? h($page_user['User']['realname']) : $page_user['User']['username'] ; ?>
					</span>
				</li>
				
				<?php if($page_user['User']['location']){ ?>
				<li><span class="label">現在地</span>
					<span class="adr">
						<?php echo h($page_user['User']['location']) ?>
					</span>
				</li>
				<?php } ?>
				
				<?php if($page_user['User']['url']){ ?>
					<li><span class="label">Web</span>
						<a target="_blank" rel="me nofollow" class="url" href="<?php echo $page_user['User']['url'] ?>" >
							<?php echo mb_strimwidth($page_user['User']['url'],0,30,'...') ?>
						</a>
					</li>
				<?php } ?>
				
				<?php if($page_user['User']['description']){ ?>
				<li id="bio">
					<span class="label">自己紹介</span> <span class="bio"><?php echo h($page_user['User']['description']); ?></span>
				</li>
				<?php } ?>
				
			</ul>
			
			<?php echo $this->renderElement('stats'); 
			//if( !isset($vars['type']) ){    // つまり /followers または //following ではない場合
				?>
				
						<li id="<?php echo $id['user_task'] ?>">
							<?php echo $html->link("{$url_user}さんのねがい",
												array('controller' => $url_user,)
											); ?>
						</li>
				<?php	if(isset($url_params) && $url_params === 'tasks/view'){ ?>
							<li id="<?php echo $id['tasks/view'] ?>" class='process'>
								<?php
								$zenbu = isset($timeline_id) ? '全' : false ;
								echo $html->link("ねがいの{$zenbu}みちのり" ,
														array('controller' => 'tasks',
															'action' => 'view',
															'task_id' => $task['Task']['id'],
															'url_user' => $url_user,
															)
													);
								if(isset($timeline_id)){	?>
									<ul>
										<li id="<?php echo $id['mitinori'] ?>" >
											<?php echo $html->link("みちのり", array('controller' => 'tasks','action' => 'view','url_user'=>$url_user,'timeline_id'=> $timeline_id ) ); ?>
										</li>
									</ul>
								<?php } ?>
							</li>					
				<?php	}	?>
							
						<li id="<?php echo $id['user_timeline'] ?>">
							<?php echo $html->link("{$url_user}さんのタイムライン",
												array('controller' => 'tasks',
												'action' => 'index',
												'url_user' => $url_user ,
												'user_timeline' => TRUE,
												'page'=>1,
												)
											); ?>
						</li>
				<?php
			//}
		}
		?>




		<?php
		//var_dump($url_user_home)
		// /home または /timeline はホームなので下記を表示する	
		//さらに /tasks/viewで表示ユーザ(url_user)とログインユーザが一緒の場合はhome扱い
		//if( isset($url_user_home) && $url_user_home === TRUE){
		if( 
			(isset($url_user_home) && $url_user_home === TRUE)
			//|| isset($url_user)
			|| ( isset($url_params) && $url_params === 'tasks/view' && (isset($login) && $url_user === $login['User']['username']) ) // /tasks/view以外では表示、またはログインしていて、urlとログインユーザが一緒なら表示
			//|| ( (isset($login) && $url_user === $login['User']['username']) ) // ログインしていて、urlとログインユーザが一緒なら表示
			
		){
			
			//loginしていれば表示
			if(isset($login) && $login){ ?>
			
				<div id="profile">
					<div class="user_icon">
						<a title="<?php echo $login['User']['username']; ?>" rel="contact" class="url" href="<?php echo $html->url('/' . $login['User']['username']); ?>">
							<!-- <img width="48" height="48" src="https://s3.amazonaws.com/twitter_production/profile_images/285831629/KERBEROS_normal.jpg" class="side_thumb photo fn" alt="KERBEROS"/> -->
							<span id="me_name"><?php echo $login['User']['username']; ?></span>
						</a>
					</div>
				</div>
				
				<?php echo $this->renderElement('stats'); ?>
			
				<li id="<?php echo $id['home'] ?>">
					<?php echo $html->link("ホーム",
										array('controller' => 'home')
									); ?>
				</li>
		<?php	if($url_params === 'tasks/view'){ ?>
					<li id="<?php echo $id['tasks/view'] ?>" class='process'>
					
					<?php
					$zenbu = isset($timeline_id) ? '全' : false ;
					echo $html->link("ねがいの{$zenbu}みちのり",
											array('controller' => 'tasks',
												'action' => 'view',
												'task_id' => $task['Task']['id'],
												'url_user' => $url_user,
												)
										);
						if(isset($timeline_id)){	?>
							<ul>
								<li id="<?php echo $id['mitinori'] ?>" >
									<?php echo $html->link("みちのり", array('controller' => 'tasks','action' => 'view','url_user'=>$url_user,'timeline_id'=> $timeline_id ) ); ?>
								</li>
							</ul>
						<?php } ?>
					</li>
		<?php	}	?>
				<li id="<?php echo $id['timeline'] ?>">
					<?php echo $html->link("タイムライン",
										array('controller' => 'timeline')
									); ?>
				</li>
		<?php		
			} 
		}
	}
		?>
		
		<li id="<?php echo $id['public_timeline'] ?>">
			<?php echo $html->link('みんなのねがい',
									array('controller' => 'tasks', 
											'action' => 'index',
											'public_timeline' => TRUE,
											'page'=>1
										)
									); ?>
		</li>

	</ul>

</div>