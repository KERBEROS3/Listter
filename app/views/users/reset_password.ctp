<div class="users form">


<?php echo $form->create('User',array('url'=>array('controller' => 'users','action' => 'reset_password','id' => false,'email'=>$params['email'],'token'=>$params['token'])));?>
	<fieldset>
 		<legend><?php __('パスワードのリセット');?></legend>
	<?php
		//echo $form->input('id');
		//echo $form->input('username');
		//echo $form->input('current_password',array('type' => 'password','label' => __('現在のパスワード',true),'autocomplete'=>'off','div'=>false));
		echo $form->input('password',array('type' => 'password','label' => __('新しいパスワード',true),'autocomplete'=>'off','div'=>false));
		echo $form->input('password_confirm',array('type' => 'password','label' => __('新しいパスワードの再入力',true),'autocomplete'=>'off','div'=>false));
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
