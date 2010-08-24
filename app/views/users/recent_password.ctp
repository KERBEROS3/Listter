<div class="users form">

<?php //echo $this->renderElement('setting-nav'); /* 設定用のタブ型navigation表示 */ ?>

<?php echo $form->create('User',array('url'=>array('controller' => 'users','action' => 'recent_password','id' => false)));?>
	<fieldset>
 		<legend><?php __('パスワードを忘れた？');?></legend>
 		<p><?php __('パスワードをリセットするために、あなたのユーザー名やメールアドレスを入力してください。') ?></p>
	<?php
	
		echo $form->input('email_or_username',array('type' => 'text','label' => __('メールアドレスやユーザー名:',true),'div'=>false));

	?>
	</fieldset>
<?php echo $form->end('Submit');?>
</div>

<!--
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('Delete', true), array('action'=>'delete', $form->value('User.id')), null, sprintf(__('Are you sure you want to delete # %s?', true), $form->value('User.id'))); ?></li>
		<li><?php echo $html->link(__('List Users', true), array('action'=>'index'));?></li>
	</ul>
</div>
-->
