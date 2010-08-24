<?php  //profile•\Ž¦A¡‚Ì‚Æ‚±‚ë–¼‘O‚¾‚¯
	//var_dump($url_params);
	//var_dump($url_user_home);
	//var_dump($url_user);
	if(
		isset($url_user) 
		&& (!isset($url_params) || ($url_params !== "home" && $url_params !== "home/timeline" && $url_params !== "public_timeline"))
		&& !( isset($login) && $url_params === 'tasks/view' && $url_user === $login['User']['username'])
	){  
	
		//if( !( isset($login) && $url_params === 'tasks/view' && $url_user === $login['User']['username']) ){
		?>
		
		<div class="profile-head">
			<h2>
				<?php //echo $url_user; ?>
				<?php echo $html->link("$url_user",array('controller'=>$url_user)); ?>
			</h2>
		</div>
		
		<?php
		//}
	}

?>