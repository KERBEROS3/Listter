<?php
class User extends AppModel {

	var $name = 'User';
	var $validate = array(
		//'id' => array('blank'),
		'username' => array(
								'max_length' => array(
													'rule' => array('maxLength', '15'),
													'message' => 'ユーザ名は15文字以下で入力してください。'
								),
								'name_alphaNumeric' => array(
													//'rule' => '/^[a-z]+([_]+[a-z0-9_]+[a-z0-9]+$|[a-z0-9]*$)/',
													//'rule' => '/^[a-z]{1}[a-z0-9_]*[a-z0-9]{1}$/',
													//'rule' => '/^[a-zA-Z]{1}[a-zA-Z0-9_]*[a-zA-Z0-9]{1}$/',
													'rule' => '/^[a-zA-Z0-9_]+$/',
													//'message' => 'ユーザ名は半角英数字の小文字のみ使用できます。(先頭は半角英字のみ、最初と最後に _ は使えません)'
													'message' => 'ユーザ名は半角英数字と _ のみです。'
								),
								'name_unique' => array(
													'rule' => 'isUnique',
													'message' => 'このユーザ名はすでに使用されています。'
								),
								'name_notEmptys' => array( 
													'rule' => 'notEmpty',
													'message' => 'このフィールドは必ず入力してください。'
								),
								'name_invalid' => array(
													'rule' => array('invalid_username'),
													'message' => 'このユーザ名はすでに使用されています。'
								),


							),

		'realname' => array(
								//'required' => false,
								
								'realname_maxLengthJP' => array(
													'rule' => array('maxLengthJP', '15'),
													'message' => '名前は15文字以下で入力してください。',
													"allowEmpty" => true ,
								),
							),

		'url' => array(
								//'required' => false,
								
								'url_maxLength' => array(
													'rule' => array('maxLength', '550'),
													'message' => 'urlは550文字以下で入力してください。',
													"allowEmpty" => true ,
								),
								'url_address' => array(
													'rule' => array('url', true), //trueでメールサーバーのホストを存在チェック
													'message' => 'urlを正しく入力してください。',
								),
							),
							
		'description' => array(
								//'required' => false,
								
								'description_maxLengthJP' => array(
													'rule' => array('maxLengthJP', '80'),
													'message' => '自己紹介は80文字以下で入力してください。',
													"allowEmpty" => true ,
								),
							),

		'location' => array(
								//'required' => false,
								
								'location_maxLengthJP' => array(
													'rule' => array('maxLengthJP', '30'),
													'message' => '現在地は60文字以下で入力してください。',
													"allowEmpty" => true ,
								),
							),

		'email' => array(
								//'required' => false,
								
								'max_length' => array(
													'rule' => array('maxLength', '255'),
													'message' => 'メールアドレスは255文字以下で入力してください。'
								),
								'mail_address' => array(
													//'rule' => array('email', true), //trueでメールサーバーのホストを存在チェック
													'rule' => array('email'), //メールサーバーのホストを存在チェックに不具合があるようなので無効化
													'message' => 'メールアドレスを正しく入力してください。',
													"allowEmpty" => true ,
								),
								'mail_unique' => array(
													'rule' => 'isUnique',
													'message' => 'このメールアドレスはすでに使用されています。'
								),
								/* 'name_notEmptys' => array( 
													'rule' => 'notEmpty',
													'message' => 'このフィールドは必ず入力してください。'
								)
								*/


							),
		
		'password' => array(
								/* 'pass_alphaNumeric' => array(
													'rule' => '/^[a-z]+[a-z0-9]*$/',
													'message' => 'パスワードは半角英数字の小文字のみ使用できます。(先頭は半角数字のみ)'
								), */
								
								'pass_notEmptys' => array( 
													'rule' => 'notEmpty',
													'message' => 'このフィールドは必ず入力してください。',
													'last' => true,
								),
								//このモデルのpassword_equalというメソッドでパスワードとチェックパスワードの同一性チェック
								'password_equal' =>array(
										'rule' => array('password_equal', 'User.password', 'User.check_password_confirm'),
										'message' => '「パスワード」と「パスワードの再入力」には確認のために同じものを入力してください。',
								),
							),
							
		'comment_mail_enabled' => array(
								'point_mailBetween' => array( 
													'rule' => array('range', -1, 2),
													'message' => '0～1の数字を入力して下さい。',
								),
							),
		'follow_mail_enabled' => array(
								'point_mailBetween' => array( 
													'rule' => array('range', -1, 2),
													'message' => '0～1の数字を入力して下さい。',
								),
							),
		'point_mail_enabled' => array(
								'point_mailBetween' => array( 
													'rule' => array('range', -1, 11),
													'message' => '0～10の数字を入力して下さい。',
								),
							),
		
		'password_confirm' => array(
								'pass_alphaNumeric' => array(
													'rule' => '/^[A-Za-z0-9!@#$%^&\*]{6,40}$/',
													'message' => '6～40文字の英数字を入力してください。'
								),
								'pass_notEmptys' => array( 
													'rule' => 'notEmpty',
													'message' => 'このフィールドは必ず入力してください。'
								)
							),
		'capcha' => array(
								'capcha_check' => array(
													'rule' => array('check_capcha'),
													'message' => '正しい上記画像の文字を入れてください。'
								),
								'capcha_notEmptys' => array( 
													'rule' => 'notEmpty',
													'message' => 'このフィールドは必ず入力してください。'
								)
							),
		'twitter_password' => array(
								'capcha_check' => array(
													'rule' => array('twitter_check'),
													'message' => 'twitter認証ができませんでした。ユーザ名またはパスワードを確認してください'
								),
							),
		'current_password' => array(
								'current_pass_notEmptys' => array( 
													'rule' => 'notEmpty',
													'message' => 'このフィールドは必ず入力してください。',
													'last' => true,
								),
								'current_pass_check' => array(
													'rule' => array('password_check'),
													'message' => 'パスワードを確認してください'
								),
							),
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed
	var $hasMany = array(
			'Task' => array('className' => 'Task',
								'foreignKey' => 'user_id',
								'dependent' => false, //本番環境ではユーザ削除したらタスクもタイムラインも自動削除にしようかな・・
								'conditions' => '',
								'fields' => '',
								'order' => '',
								'limit' => '',
								'offset' => '',
								'exclusive' => '',
								'finderQuery' => '',
								'counterQuery' => ''
			)
	);
	
	
	function invalid_username(){
		//ユーザ名として使用できない文字列リストの配列
		$invalid = array('following','followers','timeline','public_timeline', /*'pages'*/ );
		//pr($invalid);
		//$this->data['User']['username'];
		
		//pr((array_search( $this->data['User']['username'] ,$invalid) === false));
		
		return (array_search( $this->data['User']['username'] ,$invalid) === false);
		
		//return true;

	}
	
	function password_equal(){
		//var_dump($this->data['User']['password']);
		//var_dump($this->data['User']['check_password_confirm']);
		return ($this->data['User']['password'] === $this->data['User']['check_password_confirm']);
		//return true;
		//session_start();
		//var_dump('<hr>');
		//var_dump($this->Session->read('image_auth_string'));
	}
	
	function check_capcha(){
		//var_dump($a);
		//var_dump($b);
		return ($this->data['User']['capcha'] === $this->data['User']['check_capcha']);
		//return true;
		//session_start();
		//var_dump('<hr>');
		//var_dump($this->Session->read('image_auth_string'));
	}
	
	function twitter_check(){
		//もしtwitter投稿機能にチェックOFFだったら、認証チェックしない
		if(!$this->data['User']['twitter_enabled']){
			return true;
		}
		
		//twitter認証チェック //この場合はフレンドの発言を一つだけ習得することで認証
		$url = "http://twitter.com/statuses/friends_timeline.xml?count=1";
		//$username = 'kerberos3';
		$username = $this->data['User']['twitter_user'];
		//$password = 'ryota3610';
		$password = $this->data['User']['twitter_password'];

		$result = @file_get_contents($url , false, stream_context_create(array(
			"http" => array(
				"method" => "GET",
				"header" => "Authorization: Basic ". base64_encode($username. ":". $password)
			)
		)));

		//var_dump($result);

		if($result){
			//print '成功';
			return true;
		}else{
			//print '失敗';
			return false;
		}
		
		//return ($this->data['User']['password'] === $this->data['User']['check_password_confirm']);

	}
	
	/* パスワード変更の際に新旧パスワードをチェックする。 */
	function password_check(){
		//var_dump($this->data['User']['check_current_password']);
		//var_dump($this->findByid($this->data['User']['id']));
		
		$c = $this->find('count', array(
			'fields' => 'password',
			'conditions' => array('password =' => $this->data['User']['check_current_password'], 'id =' => $this->data['User']['id'] )
		));
		//pr($c);
		
		
		if($c === 1){
			return true;
		}else{
			return false;
		}
		
	}

}
?>