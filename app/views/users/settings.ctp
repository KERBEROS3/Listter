<div class="users form">

<?php echo $this->renderElement('setting-nav'); /* 設定用のタブ型navigation表示 */ ?>

<?php 
	echo $form->create('User',array('url'=>array('controller' => 'users','action' => 'settings','id' => false)));
	//echo $form->create();

?>
	<!-- <fieldset>
 		<legend><?php __('パスワードの変更');?></legend> -->
	<?php
		//echo $form->input('id');
		//echo $form->input('username');
		echo $form->input('realname',array('type' => 'text','label' => __('名前',true),'div'=>false));
		echo $form->input('email',array('type' => 'text','label' => __('メールアドレス',true),'div'=>false));
		echo $form->input('url',array('type' => 'text','label' => __('その他のURL',true),'div'=>false));
		echo $form->input('description',array('type' => 'text','label' => __('自己紹介',true),'div'=>false));
		echo $form->input('location',array('type' => 'text','label' => __('現在地',true),'div'=>false));

	?>
	<!-- </fieldset> -->
<?php echo $form->end('保存する');?>
</div>

<!--
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('Delete', true), array('action'=>'delete', $form->value('User.id')), null, sprintf(__('Are you sure you want to delete # %s?', true), $form->value('User.id'))); ?></li>
		<li><?php echo $html->link(__('List Users', true), array('action'=>'index'));?></li>
	</ul>
</div>
-->
