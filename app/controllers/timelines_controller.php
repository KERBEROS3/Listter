<?php
class TimelinesController extends AppController {

	var $name = 'Timelines';
	var $helpers = array('Html', 'Form','Javascript',);
	var $components = array('Auth','Security','PostTwitter','OauthTwitter','MyTool');
	var $uses = array('Timeline','User');

	//beforeFilterにログインしなくても良いactionを指定(カンマ区切りで複数指定)
	function beforeFilter() {
		$this->Auth->allow('index');
		
		//navi用のelementでログイン判断用にセット
		$this->set('login', $this->Auth->user() ? TRUE : FALSE );
	}



	function index() {
		//var_dump($this->Auth->user('username'));
		
		// /timeline 指定されていて、ログインしていなかったらログインページへリダイレクト
		/*
		if( isset($this->params['url_user']) && $this->params['url_user'] === 'home' && $this->Auth->user('username') === null){
			$this->redirect($this->Auth->logout());
		}
		*/
		
		//timelineの打ち間違いっぽいのは /timelineにリダイレクト(ログインしていないと/loginにredirect)
		/*
		if( $this->here === '/timelines' || $this->here === '/timelines/index' || $this->here === '/timelines/'|| $this->here === '/timelines/index/' ){
			$this->redirect('/timeline');
		}
		*/
		
		//Routerでuser_timelineが設定されている場合
		if(isset($this->params['user_timeline']) ){
			//ここの中はpublic_timelinではない,user_timelineだ
			//print 'Not public_timeline!!! <br />' . PHP_EOL;
			//var_dump($this->params['url_user']);
			
			//timelineでログインしていなかったらログインに飛ばす(url_userが指定されていないとtimeline)
			if( !isset($this->params['url_user']) && !$this->Auth->user() ){
				//$this->Session->setFlash(__('存在しないユーザです', true));
				$this->redirect('/users/login');
			}
			
			
			//url_userによってユーザ指定がされていて、ログインしていないorログインしていても自分以外が指定されている場合
			if( isset($this->params['url_user']) && $this->Auth->user('username') !== $this->params['url_user']){
				//print 'not login!!';
				//var_dump($this->params['url_user']);
				$this->User->unbindModel(array('hasMany'=>array('Task')),false);
				$username = $this->User->findByUsername($this->params['url_user']);
				if(!$username){
					//print '存在しないユーザです。';
					$this->Session->setFlash(__('存在しないユーザです', true));
					$this->redirect('/');
				} 
				//var_dump($username);
				$cond = array('Task.user_id =' => $username['User']['id']);
			}else{
				//ログインしていて、かつ自分を指定された場合。
				//print 'jibun!!';
				$cond = array('Task.user_id =' => $this->Auth->user('id'));
				
			
			}
			
		}elseif(isset($this->params['public_timeline']) ){
			//public_timelin指定があった場合
			//print 'public_timeline!!! <br />' . PHP_EOL;
			$cond = null;
		}else{
			// 通常の/timelines/ でアクセスのあった場合
			$cond = null;
		}
		
		//$this->Timeline->recursive = 1;
		$this->paginate=array(
			'conditions' => $cond,
			//'fields' => array(取得するカラム),
			//'group' => 'task_id',
			
			
			//'page' => int(数値,最初に表示するページ。デフォルトは1,'last'(小文字)も可*1),
			'limit' => 20, //int(数値：showでも可。デフォルトは20)
			'order' => array(
				 	'timeline.modified' => 'desc'
				 ),
			//'sort' => string(ソートkey：order*2 でもよい。重なった場合はsortが優先される。),
			//'direction' => string(asc or desc:デフォルトはasc)
			//'recursive' => 3
		);	
		$this->set('timelines', $this->paginate());
	}
	
	

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid Timeline.', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->set('timeline', $this->Timeline->read(null, $id));
	}

	function add() {
		//var_dump($this->data);
		//var_dump($this->Auth->user());
		
		// /timeline/add/:task_id でlogoutした状態で投稿された場合
		if( !isset($this->data['Timeline']['task_id']) && !isset($this->params['task_id'])){
			//どっちもissetされていなかったら
			$this->redirect(array('controller'=>'home'));
		}
		
		//urlかdataにtask_idがあるから取得
		//$no = isset($this->params['pass']['2']) ? $this->params['pass']['2'] : $this->data['Timeline']['task_id'];
		$no = isset($this->params['task_id']) ? $this->params['task_id'] : $this->data['Timeline']['task_id'];
		//var_dump('no!'.$no);
		if(!ctype_digit($no)){
			//if($this->Auth->user()){ $this->redirect('/home'); }
			$this->redirect(array('controller'=>'home'));
		}
		
		//Timelinへのアソシエーションをここだけ変更する
		$this->Timeline->Task->unbindModel(array('hasMany'=>array('Timeline')),false);  
		$this->Timeline->Task->bindModel(
			array(
				'hasMany'=>array('Timeline'=>array(
							'limit'=> 1,
							'order' => 'modified desc'
							
							
						) )),false
		);
		
		//そのtask_idの情報を取得  投稿前の表示用にも使用
		$tasks = $this->Timeline->Task->find('all' , 
					 array(
						'fields'=>'Task.task,Task.user_id',
						'conditions' => array('Task.id' => $no),
						//'recursive' => 1
						//'conditions' => array('Task.id' => 7)
					)
			);
		//var_dump($tasks);
		
		
		
		//取得したtaskのuser_idとauthでログインしているユーザidが違ったら不正アクセスなのでトップに飛ばす
		if($this->Auth->user('id') !== $tasks[0]['Task']['user_id']){
			$this->redirect('/');
		}
		
		//過去の%とコメントを取り出しセット
		$last = count($tasks['0']['Timeline']) - 1;
		$before = array('comment' => $tasks['0']['Timeline'][$last]['comment'], 'progress' => $tasks['0']['Timeline'][$last]['progress']);
		$this->set('before',$before);
		
		
		//タスク表示用のデータを用意
		//var_dump(compact('tasks'));
		$this->set('tasks', array($tasks['0']['Task']['id'] => $tasks['0']['Task']['task']) );
		//$this->set(compact('tasks'));
		
		//pr(key($this->viewVars['tasks']));
	
	
		if (!empty($this->data)) {
			$this->Timeline->create();
			//var_dump($this->data);
				

			$this->data['Timeline']['newer'] = 1; //addしたtimelineには最新のキーnewer = 1をセットする
			if ($flag = $this->Timeline->save($this->data)) {
				
				//同じ他task_idで、addしたデータ以外
				$this->Timeline->updateAll(
					array('Timeline.newer' => 0),
					array(	'Timeline.id !=' => $this->Timeline->getLastInsertID(), 
							'Timeline.newer =' => 1 ,
							'Timeline.task_id =' => $tasks[0]["Task"]["id"]
						)
				);
				
				
				//timeline保存時にtaskのmodifiedを更新する。
				//var_dump(date('Y-m-d h:i:s'));
				$this->Timeline->Task->id = $no; //saveFieldにはid指定が必要なので指定する
				$this->Timeline->Task->saveField('modified', date('Y-m-d H:i:s'));
				
				
				//twitter投稿部分
				$this->User->unbindModel(array('hasMany'=>array('Task')),false); //twitter投稿用のuser情報だけ欲しいのでunbind
				$user = $this->User->findById($this->Auth->user('id'));
			
				if($user['User']['twitter_enabled']){   //twitterにつぶやく設定(1)だったらつぶやく
					//最後のインサートとしたtaskのIDを取得
					$task_id = key($this->viewVars['tasks']);
					//pr($task_id);
					
					/*
					//$this->PostTwitter->username = 'kerberos3';
					$this->PostTwitter->username = $user['User']['twitter_user'];
					//$this->PostTwitter->password = 'ryota3610';
					$this->PostTwitter->password = $user['User']['twitter_password'];
					*/
					
					//ドメインを含んだ絶対URLの取得
					App::import('Helper', 'Html');
					$html = new HtmlHelper();
					$base_url = $html->url(array('controller'=>'tasks',
												'action'=>'view',
												'task_id'=>"$task_id",
												'url_user'=>$this->Auth->user('username'),
												'page'=>1,
												)
											,true);
					
					//pr($this->data);
					//twitter投稿
					$status = "{$this->data['Timeline']['comment']} - 「{$this->viewVars['tasks']["$task_id"]}」は{$this->data['Timeline']['progress']}%に! {$base_url}";
					//$status = "{$this->data['Timeline']['comment']}";
					
					//twitter投稿用に文字数オーバーの場合は削る
					//pr($status);
					//pr(mb_strlen($status, Configure::read('App.encoding')));
					
					
					
					/*
						if( ($all_count = mb_strlen($status, Configure::read('App.encoding'))) > 140 ){    //140文字以上はtwitter用にダイエット
						
							//task(ねがい)とstatus(みちのり)と以外の文字数をカウント
							//$other = mb_strlen(" - 「」は{$this->data['Timeline']['progress']}%に! {$base_url}", Configure::read('App.encoding'));
						
							
							//$count = $count - 138;   //文末に..を追加するので二文字減らして138文字
							
							//taskの文字数
							$count_task = mb_strlen($this->viewVars['tasks']["$task_id"], Configure::read('App.encoding'));
							//timelineの文字数
							$count_timeline = mb_strlen($this->data['Timeline']['comment'], Configure::read('App.encoding'));
							//taskとtimeline以外の文字数
							$other = $all_count - $count_task - $count_timeline;
							
							//taskとtimelineの文末に…を追加する可能性があるので2文字減らして138文字
							$limit = 140 - $other;   //limitがtaskとtimelin合わせて使える文字数。
							
							//pr('その他の文字数:'.$other);
							//pr('使用出来る文字数:' . $limit);	//現在は93文字
							//pr('ねがいの文字数:' . $count_task);
							//pr('<hr />みちのりの文字数:' . $count_timeline);
							
							//ねがいが25文字以上なら20文字にトリミング
							if($count_task > 25){
								$min_task = mb_substr($this->viewVars['tasks']["$task_id"],'0',20,Configure::read('App.encoding')) . '…';
								//var_dump(mb_strlen($min_task, Configure::read('App.encoding')));
								$limit = $limit - mb_strlen($min_task, Configure::read('App.encoding'));
							}else{
								$min_task = $this->viewVars['tasks']["$task_id"];
								//pr("ねがいの制限後の文字数:" . mb_strlen($min_task, Configure::read('App.encoding')));
								$limit = $limit - mb_strlen($min_task, Configure::read('App.encoding'));
								//var_dump($limit);
							}
							
							// …を追加するので、 $limit - 1 の文字数にみちのりを切り落とす
							$min_comment = mb_substr($this->data['Timeline']['comment'],'0', $limit - 1 ,Configure::read('App.encoding')) . '…';
							//pr("みちのりの制限後の文字数:" . mb_strlen($min_comment, Configure::read('App.encoding')));
							
							//pr($count);
							//$count = mb_strlen($this->viewVars['tasks']["$task_id"], Configure::read('App.encoding')) - $count; //task(ねがい)
							//pr($count);
							//$min_task = mb_strimwidth($this->viewVars['tasks']["$task_id"],'0',$count,'..',Configure::read('App.encoding'));
							//$min_task = mb_substr($this->viewVars['tasks']["$task_id"],'0',$limit,Configure::read('App.encoding'));
							//$status = "{$this->data['Timeline']['comment']} - 「{$min_task}」は{$this->data['Timeline']['progress']}%に! {$base_url}";
							$status = "{$min_comment} - 「{$min_task}」は{$this->data['Timeline']['progress']}%に! {$base_url}";
							//$status = "{$this->data['Timeline']['comment']} - 「{$min_task}…」は{$this->data['Timeline']['progress']}%に! {$base_url}";
							//var_dump(mb_strlen($status, Configure::read('App.encoding')));
						}
					*/
					
					//$statusが140文字以上ならtaskとtimelinをまとめて配列を受けて短くする
					if( $twitter = $this->MyTool->twitter_post_trim( $status , $this->viewVars['tasks']["$task_id"] , $this->data['Timeline']['comment']) ){
						$status = "{$twitter['min_comment']} - 「{$twitter['min_task']}」は{$this->data['Timeline']['progress']}%に! {$base_url}";
						//var_dump($status);
					}
					
					$check = $this->OauthTwitter->post( $status ,$user['User']['oauth_token'] , $user['User']['oauth_token_secret'] );
					//pr($check);
					
					
					//$this->PostTwitter->post("{$this->data['Timeline']['comment']} - ねがい「 {$this->viewVars['tasks']["$task_id"]} 」は{$this->data['Timeline']['progress']}%に! {$base_url}" );
					//pr($this->PostTwitter->post("test"));
				}
				
				//お遊びメッセージ、ジュイス風(東のエデンってアニメ)
				$rand = (int)substr( time(), -1 ); //タイムスタンプの下一桁をintでキャスト
				//if(mt_rand(0, 5) === 0){
				if($rand === 0){
					//$this->Session->setFlash(__('ねがいを更新しました、あなたが今後も世界の救世主たらん事を切に願います', true));
					$this->Session->setFlash(__('ねがいを更新しました、大事に育ててくださいね', true));   //普通のメッセージ
				}elseif($rand === 1){
					$this->Session->setFlash(__('ねがいを更新しました、フォースとともにあらんことを', true));
				}else{
					$this->Session->setFlash(__('ねがいを更新しました、大事に育ててくださいね', true));   //普通のメッセージ
				}
				
				$this->redirect(array('controller'=>'home'));
			} else {
				//$this->Session->setFlash(__('The Timeline could not be saved. Please, try again.', true));
				$this->Session->setFlash(__('ねがいを育てるために項目を埋めてください', true));
			}
		}else{
			//新規投稿前の表示用
			//var_dump($tasks['0']['Timeline']['0']);
			unset($tasks['0']['Timeline']['0']["comment"]); //過去の「みちのり」はいらないので削除
			//var_dump($tasks['0']['Timeline']['0']);
			$this->data['Timeline'] = $tasks['0']['Timeline']['0'];
		}

		
	}
	
	/*
	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid Timeline', true));
			$this->redirect(array('action'=>'index'));
		}
		if (!empty($this->data)) {
			if ($this->Timeline->save($this->data)) {
				$this->Session->setFlash(__('The Timeline has been saved', true));
				$this->redirect(array('action'=>'index'));
			} else {
				$this->Session->setFlash(__('The Timeline could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Timeline->read(null, $id);
		}
		$tasks = $this->Timeline->Task->find('list');
		$this->set(compact('tasks'));
	}
	*/

	function delete() {
		$id = $this->params['timeline_id'];
		if (!$id || !ctype_digit($id)) {
			$this->Session->setFlash(__('Invalid id for Timeline', true));
			$this->redirect('/home');
		}
		
		
		
		//そのtask_idの情報を取得
		$tasks = $this->Timeline->find('all' , 
					 array(
						//'fields'=>'Task.task,Task.user_id',
						'conditions' => array('Timeline.id' => $id),
						'recursive' => 2
						//'conditions' => array('Task.id' => 7)
					)
			);
		//var_dump($tasks);
		//取得したtaskのuser_idとauthでログインしているユーザidが違ったら不正アクセスなのでトップに飛ばす
		if($this->Auth->user('id') !== $tasks[0]['Task']['user_id']){
			$this->redirect('/home');
		}
		
		//timelineがひとつしか無い場合も不正なのでhomeに飛ばす
		if( count($tasks[0]["Task"]["Timeline"]) === 1 ){
			$this->Session->setFlash(__('最後の一つのタイムラインは削除できません。', true));
			$this->redirect('/tasks/view/' . $tasks[0]['Timeline']['task_id']);
		}
		
		//最新フラグの立ったtimelineだったら、最新timeline削除のフラグを立てる
		if($tasks[0]["Timeline"]["newer"] === "1"){
			$newer_delete = true;
		}
		
		if($this->Timeline->del($id)) {
		//if (TRUE) {
		
			//$newer_deleteフラグが立っていた場合は一つ前のタイムラインにnewerフラグを立てる
			if(isset($newer_delete)){
				//var_dump($tasks[0]["Task"]["Timeline"]);
				//addしたデータ以外
				$this->Timeline->updateAll(
					array('Timeline.newer' => 1),
					array('Timeline.id =' => $tasks[0]['Task']['Timeline'][1]['id'])
				);
				
			}
		
			//$this->Session->setFlash(__('Timeline deleted', true));
			$this->Session->setFlash(__('みちのりを削除しました', true));
			
			//$this->redirect("/tasks/view/{$tasks[0]['Task']['id']}");
			$this->redirect(array('controller' => 'tasks','action' => 'view','task_id' => $tasks[0]['Task']['id'],'url_user' => $this->Auth->user('username'),'page' => 1));
			
		}
	}

}
?>