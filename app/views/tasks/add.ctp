<div class="tasks form">
<?php echo $form->create('Task');?>
	<fieldset>
 		<legend><?php __('あなたのねがいは？');?></legend>
	<?php
		//var_dump($error);
		echo $form->input('task',array('label' => 'ねがい','div' => false));
		
		echo $form->input('Timeline.0.progress',array('label' => '達成度(%)','div' => false));
		/*
		if(isset($error['progress'])){
			echo '<div class="error-message">-50から120までの数字を入力してください。</div>';
		}
		*/
		
		echo $form->input('Timeline.0.comment',array('label' => 'ねがいのみちのり','div' => false));
		/*
		if(isset($error['comment'])){
			echo '<div class="error-message">入力してください。</div>';
		}
		*/
	?>
	</fieldset>
<?php echo $form->end('Submit');?>
</div>
<!--
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('List Tasks', true), array('action'=>'index'));?></li>
		<li><?php echo $html->link(__('List Timelines', true), array('controller'=> 'timelines', 'action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New Timeline', true), array('controller'=> 'timelines', 'action'=>'add')); ?> </li>
	</ul>
</div>
-->
