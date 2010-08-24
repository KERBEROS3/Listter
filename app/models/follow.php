<?php
class Follow extends AppModel {

	var $name = 'Follow';
	var $validate = array(
		'user_id' => array('numeric'),
		'follow_user_id' => array('numeric',
									'password_equal' =>array(
										'rule' => array('password_equal', 'Follow.user_id', 'Follow.follow_user_id'),
										'message' => '「パスワード」と「パスワード(チェック)」には確認のために同じものを入力してください。',
															),
		
								)
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed
	var $belongsTo = array(
		'User' => array(
			'className' => 'User',
			//'foreignKey' => 'user_id',
			'foreignKey' => 'follow_user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

	function password_equal(){
		//var_dump($data);
		//var_dump($a);
		//var_dump($b);
		//return ($this->data['Follow']['user_id'] === $this->data['Follow']['follow_user_id']);
		$c = $this->find('count',array('conditions' => array(
																'Follow.user_id' => $this->data['Follow']['user_id'],
																'Follow.follow_user_id' => $this->data['Follow']['follow_user_id']
															)));
		//var_dump($c);
		return ($c === 0 ?  TRUE :  FALSE);
	}


}
?>