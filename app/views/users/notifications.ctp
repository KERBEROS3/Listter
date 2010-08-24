<div class="users form">

<?php echo $this->renderElement('setting-nav'); /* 設定用のタブ型navigation表示 */ ?>

<?php 
	echo $form->create('User',array('url'=>array('controller' => 'users','action' => 'notifications','id' => false)));
	//echo $form->create();

?>
	<fieldset>
 		<legend><?php __('メールでお知らせ');?></legend>
	<?php
		//echo $form->input('id');
		//echo $form->input('username');
		/*
			echo $form->input('realname',array('type' => 'text','label' => __('名前',true),'div'=>false));
			echo $form->input('email',array('type' => 'text','label' => __('メールアドレス',true),'div'=>false));
			echo $form->input('url',array('type' => 'text','label' => __('その他のURL',true),'div'=>false));
			echo $form->input('description',array('type' => 'text','label' => __('自己紹介',true),'div'=>false));
			echo $form->input('location',array('type' => 'text','label' => __('現在地',true),'div'=>false));
		*/
		
		echo $form->input('follow_mail_enabled',array('type' => 'checkbox','label' => array('text' => __('わたしがフォローされたときにメールで教えて',true),'class'=>'checkbox_label'),'div'=>false));
		echo $form->input('comment_mail_enabled',array('type' => 'checkbox','label' => array('text' => __('ねがいにおうえんコメントが来たらメールで教えて',true),'class'=>'checkbox_label'),'div'=>false));
		
		echo $form->input('point_mail_enabled',array('type' => 'text','label' => __('おうえんポイントをもらったらメールで教えて',true),'div'=>true,'after'=>'おうえんポイントごとにお知らせ(0で送らない)','maxlength' => 2
));

	?>
	</fieldset>
<?php echo $form->end('保存する');?>
</div>


