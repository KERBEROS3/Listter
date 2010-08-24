<?php
	//echo $html->css('SpinBoxStyles',null,null,false);
	echo $html->css('JQuerySpinBtn',null,null,false);

	$javascript->link('jquery', false);
	//$javascript->link('message', false);
	$javascript->link('JQuerySpinBtn', false);
	$this->addScript($javascript->codeBlock('
	
		// Create group of settings to initialise spinbutton(s). (Optional)
		var myOptions = {
			min: -99,							// Set lower limit or null for no limit.
			max: 999,						// Set upper limit or null for no limit.
			step: 1,						// Set increment size.
			spinboxClass: "spinbox-active",	// CSS class to style the spinbutton. (Class also specifies url of the up/down button image.)
			upClass: "up",		// CSS class for style when mouse over up button.
			downClass: "down"		// CSS class for style when mouse over down button.
		}
	
		jQuery(document).ready(function($){

			// Initialise INPUT elements as SpinBoxes: (passing options if desired)
			$("INPUT.spinbox").SpinButton(myOptions);

		});
	'));
?>

<div class="timelines form">
<?php echo $form->create('Timeline',array('url' => 'add'));?>
<?php //echo $form->create('Timeline');?>
	<fieldset>
 		<legend><?php __('ねがいをこうしん');?></legend>
	<?php
		$progress = isset($before['progress']) ? " (現在:{$before['progress']}%)" : null ;
		$comment = isset($before['comment']) ? " (現在:" . h($before['comment']) . ")" : null ;
	
		echo $form->input('task_id',array('label' => 'ねがい'));
		//echo $form->input('task',array('label' => 'タスク'));
		echo $form->input('progress',array('label' => "達成度{$progress}",'class' => "spinbox"));
		echo $form->input('comment',array('label' => "ねがいのみちのり{$comment}"));
	?>
	</fieldset>
<?php echo $form->end('Submit');?>
</div>
<!--
	<div class="actions">
		<ul>
			<li><?php echo $html->link(__('List Timelines', true), array('action'=>'index'));?></li>
			<li><?php echo $html->link(__('List Tasks', true), array('controller'=> 'tasks', 'action'=>'index')); ?> </li>
			<li><?php echo $html->link(__('New Task', true), array('controller'=> 'tasks', 'action'=>'add')); ?> </li>
		</ul>
	</div>
-->
