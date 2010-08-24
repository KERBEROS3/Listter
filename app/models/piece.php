<?php
class Piece extends AppModel {

	var $name = 'Piece';
	var $validate = array(
		//'id' => array('numeric'),
		//'task_id' => array('numeric'),
		//'comment' => array('maxLengthJp', '150')
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed
	
	//���̃A�\�V�G�[�V�����͖{�Ԃł͂���Ȃ�
	//Piece�R���g���[����add�Ƃ��ɂ͕K�v�����ǁd�A�����܂Ńe�X�g�p�B
	var $belongsTo = array(
		'Task' => array(
			'className' => 'Task',
			'foreignKey' => 'task_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

}
?>