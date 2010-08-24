<?php
class SupportsController extends AppController {

	var $name = 'Supports';
	var $helpers = array('Html', 'Form', );
	var $components = array('Auth','Qdmail','QdmailWrap'); //Securityコンポーネントはいらないんじゃね、なので削った
	var $uses = array('Support','Task');
	
	function beforeFilter() {
		//Configure::write('debug',0); //ajaxのためにここでdebug:0にしないと余計な表示が出る(debug:1以上の時に)
		
		//Authコンポーネント関連
		//beforeFilterにログインしなくても良いactionを指定(カンマ区切りで複数指定)
		$this->Auth->allow('index','add','increment','nextcomment'); //テスト用に許可 incrementは本番でも許可
		
		//ajaxのみのコントローラの予定なので、ここもコメントアウトする
		$this->set('login', $this->Auth->user()); //loginしているかどうかview用(elementで右上のバーの内容を変化させてる)
		//var_dump($this->Auth->user());
	}
	
	function nextcomment() {
		Configure::write('debug',0); //ajaxのためにここでdebug:0にしないと余計な表示が出る(debug:1以上の時に)
		
		if( empty($this->data['nextDate']) || empty($this->data['task_id']) ){
			header("HTTP/1.1 404 Not Found");
			return true;
		}
		
		
		$this->layout = 'ajax';
		
		$res['nextDate'] = $this->data['nextDate'];
		$res['task_id'] = $this->data['task_id'];
		//pr($this->data);
		
		$this->Support->unbindModel(array('belongsTo'=>array('User')),false);
		$this->Support->bindModel(
						array(
							'belongsTo'=>array('User'=>array(
										'foreignKey' => 'supporter_user_id',
										'fields' => 'username',
										//'limit'=> 1,
										//'order' => 'Timeline.modified desc',
										//'conditions' => array('Support.supporter_user_id =' => $this->Auth->user('id')),
										//'group' => array('modified'), //fields to GROUP BY
										
															) )),false
		);
		$this->Support->unbindModel(array('belongsTo'=>array('Task')),false);
		$limit = 5;   //テスト用暫定 本番は五件ぐらいの予定
		$comment = $this->Support->find('all',
											array(
												//'fields' => 'Support.id', 
												'order' => 'Support.comment_modified desc',
												'limit'=> $limit+1, //次のコメントチェック用にlimitより一つ多く取得
												'conditions' => array('task_id'=>$res['task_id'],'comment_modified <' => $this->data['nextDate'],'comment !=' => ''),
											) 
		);
		
		if($limit < count($comment) ){
			//$comment['0']['debug'] = count($comment);
			
			//limitより一つ多くコメントがあるので、次のコメントが存在する可能性がある。
			//$del = $limit + 1 - 1; //一つ余計に取った配列を削除する。
			//$comment["$limit"] = array();
			unset($comment["$limit"]);
			
			//一つ目のコメント配列に次のコメントがあるかどうかのフラグがある。
			$comment['0']['next'] = TRUE;
			
		}
		
		//「次のコメント」用のフラグテスト
		//$comment['0']['next'] = TRUE;
		
		
		$this->set('result', json_encode($comment));
		$this->render(null, null, VIEWS . DS . 'ajax.ctp'); // Ajax 用共通 view
	}
	
	function comment() {
		Configure::write('debug',0); //ajaxのためにここでdebug:0にしないと余計な表示が出る(debug:1以上の時に)
		$this->layout = 'ajax';
		
		//ログインしていないとコメントできないのでエラー
		if(!$this->Auth->user()){
			header("HTTP/1.1 404 Not Found");
			return true;
		}
		
		//自分のタスクだったらコメントできない処理
		//$task = $this->Task->findById( $this->data['Support']['task_id'] );
		$task_check = $this->Task->find('count', array(
			//'fields' => 'DISTINCT Article.user_id',  //fields に配列を渡してはいけません。DISTINCT カウントを行うフィールドだけを指定
			'conditions' => array('id =' => $this->data['Support']['task_id'],'user_id =' => $this->Auth->user('id') ),
		));
		if($task_check > 0){         //指定したタスクが自分のだったら一件あるのでエラー
			header("HTTP/1.1 404 Not Found");
			return true;
		}
		//pr($task_check);

		
		//必要なデータ
		//$this->data['Support']['id'] = 999;
		//$this->data['Support']['comment'] = 'コメントテストだよ!!';
		//$this->data['Support']['task_id'] = 7;
		$this->data['Support']['supporter_user_id'] = $this->Auth->user('id');
		$this->data['Support']['comment_modified'] = date("Y-m-d H:i:s");
		
		
		
		//既に対象タスクにおうえんしているかチェック
		$this->Support->unbindModel(array('belongsTo'=>array('User')),false);
		//$this->Support->unbindModel(array('belongsTo'=>array('Task')),false);
		$ret = $this->Support->find('all', array('conditions' => array('supporter_user_id =' => $this->Auth->user('id'),'task_id =' => $this->data['Support']['task_id'] )));
		//pr($ret);
		
		if($ret){
			//既にこのタスクに対しておうえんしていればid取得してupdate
			$this->data['Support']['id'] = $ret['0']['Support']['id'];
			
			//さらにおうえんポイントをjsonで渡す
			$res['supporter_point'] = $ret['0']['Support']['points'];
			
		}else{
			//このタスクに対しておうえんしていないので
			//おうえんポイント 0をjsonで渡す
			$res['supporter_point'] = 0;
			
		}
		
		//pr($this->data);
		
		//$this->Support->set( $this->data );
		//if ($this->Support->validates()) {
		if ($this->Support->save($this->data)) {
			// バリデーションが成功した場合のロジックをここに書く
			//pr('save成功!');
			
			//コメント投稿に成功したらお知らせメール(コメントを受け取った人にメール)
			//メールの送信準備
			$this->loadModel('User'); //user モデルをload
			//$user = $this->User->read();
			$this->User->unbindModel(array('hasMany'=>array('Task')),false); //メール送信用のuser情報だけ欲しいのでunbind
			$user = $this->User->findAllById( $ret['0']['Task']['user_id'] );
			//$res['test'] = $user;
			//$user = $this->User->findAllById( $ret['0']['Task']['user_id'] );
			
			if( $user[0]['User']['email'] && $user[0]['User']['comment_mail_enabled'] ){
				//commentした人の名前情報
				$comment_name = $this->Auth->user('realname') ? $this->Auth->user('realname') : $this->Auth->user('username') ;
				//commentした人の詳しい名前情報
				$comment_2name = $this->Auth->user('realname') ? "{$this->Auth->user('realname')} / {$this->Auth->user('username')}" : $this->Auth->user('username') ;
				
				//commentを貰った人の詳しい名前情報
				$get_2name = $user[0]['User']['realname'] ? "{$user[0]['User']['realname']} ({$user[0]['User']['username']})" : $user[0]['User']['username'] ;
				
				//ドメインを含んだ絶対URLの取得
				App::import('Helper', 'Html');
				$html = new HtmlHelper();
				$base_url = $html->url(array('controller'=> $this->Auth->user('username') ),true);
				$home_url = $html->url(array('controller'=> 'home' ),true);
				
				//メールの送信
				$this->QdmailWrap->address = $user[0]['User']['email'];
				$this->QdmailWrap->subject = "{$comment_name}から「おうえんコメント」が届きました";
				$this->QdmailWrap->message = "こんにちは、{$get_2name}さん。\n\n{$comment_2name} さんから\n「{$ret['0']['Task']['task']}」におうえんコメントが届きました。\n{$home_url}\n\n--\nこのユーザーをフォローしたい場合はこちらまで：{$base_url}\n \nリスッターから「おうえんコメント」のメールを受信したくない場合は、今すぐ解除できます。リスッターからのメール選択について再度登録や変更をしたい場合は、自分のアカウントから「設定」へ行きお知らせ機能を操作してください。";
				
				if( $this->QdmailWrap->post() ){
					//メール送信に成功したら
				}
			}
			
		} else {
			// バリデーションが失敗した場合のロジックをここに書く
			$errors = $this->Support->invalidFields(); // validationErrors 配列を含むデータを取得する
			//pr($errors);
			
			$res['errors'] = $errors;
			
		}
		
		$this->set('result', json_encode($res));
		$this->render(null, null, VIEWS . DS . 'ajax.ctp'); // Ajax 用共通 view
		
		
		/*
		if ($this->Support->save($this->data)) {
			
		}
		*/
		
	}
	
	function increment() {
		Configure::write('debug',0); //ajaxのためにここでdebug:0にしないと余計な表示が出る(debug:1以上の時に)
		//$this->view = "View"; // DebugKitとQdmailを一緒に使うときに必要
		
		if(!$this->Auth->user()){
			header("HTTP/1.1 404 Not Found");
			return true;
		}
		
		$this->layout = 'ajax';
		
		//urlの:task_idが存在することを確認
		$this->Task->unbindModel(array('hasMany'=>array('Timeline')),false);
		/*
		$this->Task->bindModel(
						array(
							'hasOne'=>array('Support'=>array(
										'foreignKey' => 'task_id',
										//'limit'=> 1,
										//'order' => 'Timeline.modified desc',
										'conditions' => array('Support.supporter_user_id =' => $this->Auth->user('id')),
										//'group' => array('modified'), //fields to GROUP BY
										
															) )),false
		);
		*/
		$task = $this->Task->find('all', array('conditions' => array('Task.id' => $this->params['task_id'])));
		//$task = $this->Task->findAll(('1 = 1 group by Task.id', 'Task.*,sum(points) as "Task.points"')
		
		//pr($task);
		//$res["debug"]["task"] = $task['0']['Task']['task']; //対象のタスク名
		
		//$res["debug"]["task"] = $task;
		
		
		//指定されたtask_idが存在する && 自分のタスクじゃないことを確認		
		if($task && $this->Auth->user('id') !== $task['0']['Task']['user_id'] ){
			//pr('task_id存在するよ！ && 自分のタスクじゃないよ！！');
			
			//supportが存在しない事を前提に基本のデータをセット
			$data['Support'] = array('task_id'=> $this->params['task_id'], 'supporter_user_id'=> $this->Auth->user('id'),'points'=> 1 );
			
			
			$json_points = 1;
			$res['supporter_point'] = 1; //初のおうえんポイントの場合は1ポイントを設定
			//対象タスクの配列全ての合計を得るループ
			foreach($task['0']['Support'] as $sup){
				//すでにsupportがあるかチェック
				if($sup['supporter_user_id'] == $this->Auth->user('id')){
					//すでにsupportが存在すればidと1加算するデータを用意
					$data['Support']['id'] = $sup['id'];
					$data['Support']['points'] = 1 + $sup['points'];
					$res['supporter_point'] = 1 + $sup['points']; //おうえんポイントを贈った人だけのこのタスクへのポイントをjsonで返す
				}
				//このタスクの合計応援ポイントをjson用に用意
				$json_points += $sup['points'];
			}
			
			$res['points'] = $json_points;
			
			//if($task['0']['Support']['id']){
			/*
			if(isset($task['0']['Support']['id'])){
				pr('すでにsupportがあるよ！');
				$data['Support']['id'] = $task['0']['Support']['0']['id'];
				$data['Support']['points'] = 1 + $task['0']['Support']['points'];
				
				$res['points'] = $data['Support']['points'];
			}
			*/
			
			$this->Support->create();
			if ($this->Support->save($data)) {
				
				$this->loadModel('User'); //user モデルをload
				//$user = $this->User->read();
				$this->User->unbindModel(array('hasMany'=>array('Task')),false); //twitter投稿用のuser情報だけ欲しいのでunbind
				$user = $this->User->findAllById( $task['0']['Task']['user_id'] );
				//$res["debug"]["user"] = $user;
				
				$res["debug"]["amari"] = 2 % 2;
				
				if( $user[0]['User']['email'] && ($json_points % $user[0]['User']['point_mail_enabled']) === 0 ){
					//ポイントを贈った人の名前情報
					$point_name = $this->Auth->user('realname') ? $this->Auth->user('realname') : $this->Auth->user('username') ;
					//ポイントを贈った人の詳しい名前情報
					$point_2name = $this->Auth->user('realname') ? "{$this->Auth->user('realname')} / {$this->Auth->user('username')}" : $this->Auth->user('username') ;
					
					//pointを貰った人の詳しい名前情報
					$get_2name = $user[0]['User']['realname'] ? "{$user[0]['User']['realname']} ({$user[0]['User']['username']})" : $user[0]['User']['username'] ;
					
					//ドメインを含んだ絶対URLの取得
					App::import('Helper', 'Html');
					$html = new HtmlHelper();
					$base_url = $html->url(array('controller'=> $this->Auth->user('username') ),true);
					$home_url = $html->url(array('controller'=> 'home' ),true);
					
					//メールの送信
					$this->QdmailWrap->address = $user[0]['User']['email'];
					$this->QdmailWrap->subject = "{$point_name}から「おうえんポイント」が届きました";
					$this->QdmailWrap->message = "こんにちは、{$get_2name}さん。\n\n{$point_2name} さんから\n「{$task['0']['Task']['task']}」におうえんポイントが届きました。\n{$home_url}\n\n--\nこのユーザーをフォローしたい場合はこちらまで：{$base_url}\n \nリスッターから「おうえんポイント」のメールを受信したくない場合は、今すぐ解除できます。リスッターからのメール選択について再度登録や変更をしたい場合は、自分のアカウントから「設定」へ行きお知らせ機能を操作してください。";
					
					if( $this->QdmailWrap->post() ){
						//メール送信に成功したら
					}
				}
			}
			
		}else{
			header("HTTP/1.1 404 Not Found");
		}
		

		$this->set('result', json_encode($res));
		$this->render(null, null, VIEWS . DS . 'ajax.ctp'); // Ajax 用共通 view
	}

	/*

	function index() {
		$this->Support->recursive = 0;
		$this->set('supports', $this->paginate());
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid Support.', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->set('support', $this->Support->read(null, $id));
	}

	function add() {
		//pr($this->data);
		
		if (!empty($this->data)) {
			$this->Support->create();
			if ( $this->Support->save($this->data)) {
				$this->Session->setFlash(__('The Support has been saved', true));
				$this->redirect(array('action'=>'index'));
			} else {
				$this->Session->setFlash(__('The Support could not be saved. Please, try again.', true));
			}
		}
		$tasks = $this->Support->Task->find('list');
		$users = $this->Support->User->find('list');
		$this->set(compact('tasks', 'users'));
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid Support', true));
			$this->redirect(array('action'=>'index'));
		}
		if (!empty($this->data)) {
			if ($this->Support->save($this->data)) {
				$this->Session->setFlash(__('The Support has been saved', true));
				$this->redirect(array('action'=>'index'));
			} else {
				$this->Session->setFlash(__('The Support could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Support->read(null, $id);
		}
		$tasks = $this->Support->Task->find('list');
		$users = $this->Support->User->find('list');
		$this->set(compact('tasks','users'));
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for Support', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Support->del($id)) {
			$this->Session->setFlash(__('Support deleted', true));
			$this->redirect(array('action'=>'index'));
		}
	}
	
	*/

}
?>