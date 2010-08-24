<?php
	if ($session->check('Message.flash')) { $session->flash(); }
	if  ($session->check('Message.auth')) $session->flash('auth');
	
	
	echo $form->create('User', array('action' => 'login'));
	echo '<fieldset>';
	echo "<legend>" . __('ログイン',true) . "</legend>";
	echo $form->input('username',array('label' => __('ユーザ名',true),'div'=>false));
	echo $html->link('パスワードを忘れた？',array('controller' => 'users','action' => 'recent_password'), array('class'=>'recent_password','title'=>'パスワードを忘れた？','tabindex'=>'-1'));
	echo $form->input('password',array('label' => __('パスワード',true),'div'=>false));
	
	//echo $form->input('auto_login', array('type' => 'checkbox', 'label' => 'Log me in automatically?'));
	//echo $form->checkbox('remember_me').'次回から自動的にログイン';
	echo $form->input('remember_me', 
				array(
					//'id' => $k, 
					'type' => 'checkbox', 
					//'value' => $k, 
					//'label' => '次回から自動的にログイン', 
					'label' => array('text'=>'次回から自動的にログイン','id'=>'auto_login'), 
					'div' => false,
				)
			);
	echo '</fieldset>';
	
	echo $form->end( __('ログイン',true) );
	
?>
