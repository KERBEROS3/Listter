<?php
class Support extends AppModel {

	var $name = 'Support';
	var $validate = array(
		//'id' => array('numeric'),
		'task_id' => array('numeric'),
		'supporter_user_id' => array('numeric'),
		'points' => array('numeric'),
		'comment' => array(
						'comment_valid' => array(
										'rule' => array('minLengthJP', '1'),
										'message' => '1文字以上を入力してください。'
									),
								array(
										'rule' => array('maxLengthJP', '100'),
										'message' => '100文字以下を入力してください。'
									),
								array(
										'rule' => array('space_only'),
										'message' => '空白以外を入力してください。'
									)
		)
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed
	var $belongsTo = array(
		'Task' => array(
			'className' => 'Task',
			'foreignKey' => 'task_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'supporter_user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

}
?>