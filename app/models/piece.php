<?php
class Piece extends AppModel {

	var $name = 'Piece';
	var $validate = array(
		//'id' => array('numeric'),
		//'task_id' => array('numeric'),
		//'comment' => array('maxLengthJp', '150')
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed
	
	//このアソシエーションは本番ではいらない
	//Pieceコントロールのaddとかには必要だけど‥、あくまでテスト用。
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