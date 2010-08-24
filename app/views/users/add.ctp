<script language="javascript">
var count = 0
function action(){
count++;
if(document.images)document.capcha.src = '/users/capcha_img/' + count + '.png';

}
</script>

<?php
	//Auth コンポーネントが吐き出すエラーメッセージを表示する
	if ($session->check('Message.flash')) {
		$session->flash();
	}
	if ($session->check('Message.auth')) {
		$session->flash('auth');
	}
?>

<div class="users form">
<?php echo $form->create('User');?>
	<fieldset>
		
 		<!-- <legend><?php __('Add User');?></legend> -->
 		<legend><?php __('りすったーに参加しましょう');?></legend>
	<?php
		echo $form->input('username',array( 'label' => __('ユーザー名',true),'div'=>'required' ));
		echo $form->input('email',array( 'label' => __('メールアドレス',true) ));
		echo $form->input('password',array( 'label' => __('パスワード',true) ));
		echo $form->input('password_confirm',array('type'=>'password','label'=> __('パスワードの再入力',true) ));
	?>
	<div class="capcha"><img src="<?php echo $html->url('/users/capcha_img/0.png') ?> " alt="認証用の英数字" name="capcha" />
	<a href="javascript:action()" tabindex="-1">別の画像にする</a>
	<?php
		echo $form->input('capcha',array('label'=>'上記の文字を入力してください'));
	?>
	</div>
	
	</fieldset>
<?php echo $form->end('Submit');?>
</div>

<!--
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('List Users', true), array('action'=>'index'));?></li>
	</ul>
</div>
-->
