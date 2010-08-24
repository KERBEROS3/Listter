<div class="users form">


<?php echo $this->renderElement('setting-nav'); /* 設定用のタブ型navigation表示 */ ?>

<?php 

?>

<?php echo $form->create(null,array('url' => array('controller' => 'users', 'action' => 'twitter','id' => false)) );?>
	<fieldset>
 		<legend><?php __('Twitter User');?></legend>
	<?php
		//echo $request_link;
		//oauth認証が
		if(isset($request_link)){
			echo '<div>';
			echo $html->link(__('Twitterへ投稿するために認証する', true), $request_link);
			echo '</div>';
		}
		
		//oauth認証が有効ならチェックを表示する
		//if(isset($oauth_enabled)){
			echo $form->input('twitter_enabled',array('type' => 'checkbox','label' => array('text' => __('ねがいをTwitterにつぶやく',true),'class'=>'checkbox_label'),'div'=>false));
		//}
		//echo $form->input('twitter_user',array('label' => __('Twitter user',true),'div'=>false));
		//echo $form->input('twitter_password',array('label' => __('Twitter Password',true),'type'=>'password','div'=>false));
	?>
	</fieldset>
	<div class="submit">
		<?php
			//旧twitter認証(ユーザ名とパスワード)用の回数制限発動時処理
			if(isset($twitter_stopped)){
				echo $form->button('保存する', array('type'=>'submit','disabled'=>'disabled'));
			}else{
				echo $form->button('保存する', array('type'=>'submit'));
			}
		?>
	</div>
<?php echo $form->end(); ?>
</div>

<!--
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('Delete', true), array('action'=>'delete', $form->value('User.id')), null, sprintf(__('Are you sure you want to delete # %s?', true), $form->value('User.id'))); ?></li>
		<li><?php echo $html->link(__('List Users', true), array('action'=>'index'));?></li>
	</ul>
</div>
-->