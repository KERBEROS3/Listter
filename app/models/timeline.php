<?php
class Timeline extends AppModel {

	var $name = 'Timeline';
	var $recursive = 1;
	var $validate = array(
		'task_id' => array(
			'numeric' => array(
				'rule' =>'numeric',
				'message' => '-99から999までの半角数字を入力してください。',
				'on' => 'update',	//update時のみこのvalidateを実行する
			),
		),
		'progress' => array(
			'numeric' => array(
				'rule' =>'numeric',
				'message' => '-99から999までの半角数字を入力してください。'
			),
			'range' => array(
				'rule' => array('range', -100, 1000),
				'message' => '-99から999までの半角数字を入力してください。'
			),
		),
		'comment' => array(
			'minLength' => array(
				'rule' => array('minLengthJP', '1'),
				'message' => 'ねがいの今の状態を、1文字以上入力してください'
			),
			'maxLength' => array(
				'rule' => array('maxLengthJP', '100'),
				'message' => '100文字以下で入力してください'
			),
			array(
					'rule' => array('space_only'),
					'message' => '空白以外を入力してください。'
			)
		)
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed
	var $belongsTo = array(
			'Task' => array('className' => 'Task',
								'foreignKey' => 'task_id',
								'conditions' => '',
								'fields' => '',
								'order' => ''
			)
	);

}
?>