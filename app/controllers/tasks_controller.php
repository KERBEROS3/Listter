<?php
class TasksController extends AppController {

	var $name = 'Tasks';
	var $helpers = array('Html', 'Form','Javascript','Ajax','Text');
	//AuthComponentの宣言
	var $components = array('Auth','Security','RequestHandler','PostTwitter','OauthTwitter','Cookie','MyTool' );
	var $uses = array('Task','Timeline','User','Follow','Support');

	
	//beforeFilterにログインしなくても良いactionを指定(カンマ区切りで複数指定)
	function beforeFilter() {
		$this->pageTitle = 'Listter'; //標準のタイトル
	
		//Authコンポーネント関連
		$this->Auth->allow('index','view','top');
		
		if( $id = $this->Auth->user('id') ){
			$this->User->unbindModel(array('hasMany'=>array('Task')),false); //twitter投稿用のuser情報だけ欲しいのでunbind
			$this->set('login', $this->User->read(null, $this->Auth->user('id')) );
		}
		
		/*
		$this->set('login', $this->Auth->user()); //loginしているかどうかview用(elementで右上のバーの内容を変化させてる)
		pr($this->Auth->user());
		$this->User->unbindModel(array('hasMany'=>array('Task')),false);
		pr($this->User->read(null, $this->Auth->user('id')));
		*/
	}


	function index() {
		//pr($this);
		//pr($this->Cookie->read('autoLogin'));
		
		//Configure::write('debug',0);
		//pr($this->Session->read('Auth.User'));
		//pr($this->viewVars);
		
		//echo $javascript->link('prototype');
		
		// /home 指定されていて、ログインしていなかったらログインページへリダイレクト
		if( isset($this->params['url_user']) && $this->params['url_user'] === 'home' && $this->Auth->user('username') === null){
		//if( isset($this->params['url_user']) && $this->params['url_user'] === 'home' && !$this->Auth->user()){
			//$this->redirect($this->Auth->logout());
			//var_dump($this->Auth->user());
			$this->redirect(array('controller'=>'users','action'=>'login'));
		}
		
		//ユーザ指定(/home含む)がされている場合はviewでpagenationにurlを渡すのでset
		if(isset($this->params['url_user']) ){
			$this->set('url_params', $this->params['url_user']);
			//$this->set('url_user', $this->params['url_user']);
		}
		// /public_timeline指定がされている場合はviewでpagenationに「public_timeline」を渡すのでset
		if(isset($this->params['public_timeline']) ){
			$this->set('url_params', 'public_timeline');
		}
		// /user_timeline指定がされている場合はviewでpagenationに「user_timeline」を渡すのでset
		if(isset($this->params['user_timeline']) ){
			//var_dump($this->params['url_user']);
			//user_timelineが設定されているが、user指定がされていない場合はredirect
			if($this->params['url_user'] === null){
				$this->redirect('/public_timeline');
			}
			//$this->set('url_user', $this->params['url_user'] );
			$this->set('url_params', "{$this->params['url_user']}/timeline");
			$this->set('user_timeline', TRUE);
		}
		
		//Timelineへのアソシエーションをここだけ変更する
		$this->Task->unbindModel(array('hasMany'=>array('Timeline')),false);
		$this->Task->bindModel(
			array(
				'hasOne'=>array('Timeline'=>array(
							//'limit'=> 1,
							//'order' => 'Timeline.modified desc',
							//'conditions' => array('Timeline.id =' => 'Task.new_tl_id'),
							//'group' => array('modified'), //fields to GROUP BY
							
						) )),false
		);
		//Userのいらないアソシエーションunbind
		$this->User->unbindModel(array('hasMany'=>array('Task')),false);
		
		if(isset($this->passedArgs['user']) || isset($this->params['url_user'])){   //ユーザ指定がされている場合(/home含む)
			//urlでユーザ名指定方法が二つあるので選択 ここでの$user_name $user は url上(url_user)のこと
			$user_name = isset($this->passedArgs['user']) ? $this->passedArgs['user'] : $this->params['url_user'] ;
			// /homeだったらTRUEに設定
			if(isset($this->params['url_user']) && $this->params['url_user'] === 'home'){
				$this->set('url_user_home', TRUE); 
			}
			
			// /home の場合はログインしていたらユーザ名を取得する。
			//$user_name = isset($this->params['url_user']) && $this->params['url_user'] === 'home' ? $this->Auth->user('username') : $user_name;
			if( isset($this->params['url_user']) && $this->params['url_user'] === 'home' ){
				$user_name = $this->Auth->user('username');
			}else{
				// /homeじゃない場合はurlはユーザ名の大文字小文字自由なので、ユーザ名を正しいものに補正
				$user_temp = $this->User->find('first', array('fields' => array('username'),'conditions' => array('username' => $user_name) ));
				//pr( $user_temp );
				$user_name = $user_temp['User']['username'];
				
				// /home以外はプロフィールの詳細表示をするので用意
				//pr($user_name);
				//$this->User->findByUsername($user_name);
			}
			
			$this->set('url_user', $user_name); //タスク追加用フォーム表示非表示判断のためにユーザネームをset そのほかにも結構使ってるよ
			if(($user = $this->User->findByUsername($user_name)) === false){ 
																			$this->Session->setFlash(__('That page doesn\'t exist! ' , true));
																			$this->redirect('/public_timeline'); //指定されたユーザネームがなければ'/'へ
																		}; 
			//このページのユーザのデータをset
			$this->set('page_user', $user);
			//pr($user);
			
			
			
			if(isset($this->params['url_user']) && $this->params['url_user'] === 'home'){
				// /homeのSQL設定
				
				// show_flag 
				// :0 殿堂入りは非表示
				// :1 殿堂入りとそれ以外も表示
				// :2 殿堂入りのみ表示
				//var_dump($user['User']['complete_show_flag']);
				
				// ここでtaskのcompleted(殿堂入り)の表示設定を切り替える
				if($user['User']['complete_show_flag'] === "0"){
					$cond = array("user_id =" => "{$user['User']['id']}",'Timeline.newer =' => '1','Task.completed !=' => '1');
				}elseif($user['User']['complete_show_flag'] === "1"){
					$cond = array("user_id =" => "{$user['User']['id']}",'Timeline.newer =' => '1');
				}else{
					$cond = array("user_id =" => "{$user['User']['id']}",'Timeline.newer =' => '1','Task.completed !=' => '0');
				}
			}else{
				// /:user_name のSQL設定
				$cond = array("user_id =" => "{$user['User']['id']}",'Timeline.newer =' => '1');
			}
			
			//$cond = array("user_id =" => "{$user['User']['id']}");
			$order = array('Timeline.modified' => 'desc');
			
			
			//このユーザをフォローしているかチェック
			//pr($user);
			//pr($this->Auth->user('id'));
			//pr($this->viewVars['page_user']['User']['id']);
			//$follow = $this->Follow->find('count', array('conditions' => array('user_id' => $this->viewVars['login']['User']['id'], 'follow_user_id' => $this->viewVars['page_user']['User']['id'] )));
			$this->Follow->unbindModel(array('belongsTo'=>array('User')),false); //user情報をセットで必要なケース無いのでunbind
			if($this->Auth->user('id') !== $user['User']['id']){ //自分をフォローしようとしている場合はお門違いなのでfindしない
				$follow = $this->Follow->find('count', array('conditions' => array('user_id' => $this->Auth->user('id'), 'follow_user_id' => $user['User']['id'] )));
				if($follow === 1){
									$this->set('this_user_follow', TRUE); //フォローしてればTRUE
				}
				//pr($this->viewVars);
				$user_id = $user['User']['id']; //url上のユーザのuser_idを用意
				//$colum = array('following'=>'user_id','follower'=>'follow_user_id');
			}else{   //loginしているユーザだった場合
				$user_id = $this->Auth->user('id');  //ログインしているユーザのuser_idを用意
				//$colum = array('following'=>'user_id','follower'=>'follow_user_id');
			}
			//pr('$user_id: '.$user_id);
			
			//stats用のデータを用意	
			//フォローしている(folloing)・されている(follower)数をカウント
			$stats['folloing'] = $this->Follow->find('count', array('conditions' => array( 'user_id' => $user_id)) );
			//$stats['folloing'] = $this->Follow->find('count', array('conditions' => array( $colum['following'] => $user_id)) );
			//pr($stats['folloing']);
			$stats['follower'] = $this->Follow->find('count', array('conditions' => array( 'follow_user_id' => $user_id)) );
			//$stats['follower'] = $this->Follow->find('count', array('conditions' => array( $colum['follower'] => $user_id)) );
			//pr($stats['follower']);
			//願いの更新数
			$stats['timeline_count'] = $this->Timeline->find('count', array('conditions' => array('user_id' => $user_id)) );
			//pr($stats['timeline_count']);
			$this->set('stats', $stats);
			
			// /:url_user/timeline/* (home含む)でアクセスした場合はconditionを変更(user_timelineが設定されている場合)
			if( isset($this->params['user_timeline']) ){
				
				// /home/timelinのときだけfollowしたユーザのをsqlに追加する
				if( $this->params['url_user'] === 'home' ){
					// /home/timeline ではユーザ名が欲しいのでbind
					$this->Task->bindModel(
									array(
										'belongsTo'=>array('User'=>array(
													//'limit'=> 1,
													//'order' => 'Timeline.modified desc',
													//'conditions' => array('Timeline.id =' => 'Task.new_tl_id'),
													//'group' => array('modified'), //fields to GROUP BY
													
																		) )),false
					);
					
					//$this->Follow->unbindModel(array('belongsTo'=>array('User')),false);
					
					//pr($user);
					//pr($follow_list = $this->Follow->findAllByUserId($user['User']['id']));
					$follow_list = $this->Follow->findAllByUserId($user['User']['id']);  //自分がフォローしてる(follow_)user_idを取得
					//pr($follow_list);
					foreach($follow_list as $v){
						$follow_id_list[] = $v['Follow']['follow_user_id'];
					}
				}
				
				$follow_id_list[] = $user['User']['id']; //用意しておいた自分のuser_id
				//pr($follow_id_list);
								
				//$cond = array("user_id" => array("{$user['User']['id']}",'28'));
				$cond = array("user_id" => $follow_id_list);
				
			}
			
		}elseif( isset($this->params['public_timeline']) ){    //    /public_timeline指定があったら
			
			//public_timeline　ではユーザ名が欲しいのでbind
			$this->Task->bindModel(
									array(
										'belongsTo'=>array('User'=>array(
													//'limit'=> 1,
													//'order' => 'Timeline.modified desc',
													//'conditions' => array('Timeline.id =' => 'Task.new_tl_id'),
													//'group' => array('modified'), //fields to GROUP BY
													
																		) 
														)
									)
									,false
								);
			
		
			$cond = array();
			$order = array('Timeline.modified' => 'desc');
			$this->set('url_user', $this->Auth->user('username')); //タスク追加用フォーム表示非表示判断のためにユーザネームをset	
		}else{  //全ユーザのtask表示　いらないかも？?
			// /tasks/indexは現在publi_timelin扱いだが廃止予定
			
			$cond = array('Timeline.newer =' => '1');
			$order = array('Timeline.modified' => 'desc');
			$this->set('url_user', $this->Auth->user('username')); //タスク追加用フォーム表示非表示判断のためにユーザネームをset
		}
		
		//var_dump($this->passedArgs);
		//var_dump($this->passedArgs['user']);
		//var_dump($this->params['url_user']);
		//var_dump( $user['User']['id'] ); 
		
		//自分ページかどうかのview用変数をset、各種の自分のページのみに表示するエレメント等の表示に使用
		if($this->viewVars['url_user'] === $this->Auth->user('username') && !isset($this->params['public_timeline'])){
			//print 'mypageだよ！！';
			$this->set('my_page', TRUE);
		}
		
		//Supportのコメントあるものを指定件数だけ欲しいので
		$this->Task->unbindModel(array('hasMany'=>array('Support')),false); //一旦Supportをunbind
		$this->Task->bindModel(
				array(
					'hasMany'=>array('Support'=>array(
								'limit'=> 3, //コメントあるものの表示件数 (conditionでコメントあるものだけにしてるので)
								'order' => 'Support.comment_modified desc',
								'conditions' => array('or' => array('comment !=' => '','supporter_user_id =' => $this->Auth->user('id'))), //コメント表示用にコメントが空でないSupportをアソシエーション
								//'group' => array('modified'), //fields to GROUP BY
								
													) )),false
		);
		$this->Task->Support->unbindModel(array('belongsTo'=>array('User')),false); //一旦SupportのUserをunbind
		$this->Task->Support->bindModel(
						array(
							'belongsTo'=>array('User'=>array(
							      'foreignKey' => 'supporter_user_id',
							      'fields' => array('id','username'),     //色々漏れるのでUserのカラムを制限
										//'limit'=> 1,
										//'order' => 'Timeline.modified desc',
										//'conditions' => array('Timeline.id =' => 'Task.new_tl_id'),
										//'group' => array('modified'), //fields to GROUP BY
										
							))
						)
						,false
		);
		
		//おうえんコメントで自分のコメントあるかどうかのチェックに必要なので、unbind後にuser_idだけ再度bind
		$this->Support->unbindModel(array('belongsTo'=>array('Task')),false);
		/*
		$this->Support->bindModel(
				array(
					'belongsTo'=>array('Task'=>array(
								'foreignKey' => 'task_id',
								'fields' => 'user_id',
								//'limit'=> 3, //コメントあるものの表示件数 (conditionでコメントあるものだけにしてるので)
								//'order' => 'Support.modified desc',
								//'group' => array('modified'), //fields to GROUP BY
								
													) )),false
		);
		*/
		$this->Timeline->unbindModel(array('belongsTo'=>array('Task')),false);
		
		$this->paginate=array(
			//'conditions' => array("user_id =" => "{$user['User']['id']}"),
			'conditions' => $cond,
			//'fields' => array('Task.*','Timeline.*',),
			//'fields' => array('Task.id', 'Task.task', 'Task.created', 'Task.modified', 'Task.user_id', 'User.id', 'User.username', 'User.created', 'User.modified','User.twitter_enabled', 'User.realname', 'User.url', 'User.description', 'User.location', 'Timeline.id', 'Timeline.task_id', 'Timeline.progress', 'Timeline.comment', 'Timeline.modified', 'Timeline.newer'),
			//'group' => array('Timeline.task_id'),
			//'page' => int(数値,最初に表示するページ。デフォルトは1,'last'(小文字)も可*1),
			'limit' => 20, //int(数値：showでも可。デフォルトは20)
			'order' => $order,
			/* 'order' => array(
				 	'task.modified' => 'desc',
				 	//'Timeline.modified' => 'asc',
				 ) */
			//'sort' => string(ソートkey：order*2 でもよい。重なった場合はsortが優先される。),
			//'direction' => string(asc or desc:デフォルトはasc)
			'recursive' => 2
		);


		$tasks = $this->paginate();
		
		$i=0;
		foreach($tasks as $task){
			//modifiedをタイムスタンプにし、現在のタイムスタンプから引く、経過タイムスタンプを取得ご経過時間を返す関数
			$tasks[$i]['Task']['modified_elapsed'] = $this->Task->strToElapsed($task['Timeline']['modified']);
			$tasks[$i]['Task']['created_elapsed'] = $this->Task->strToElapsed($task['Task']['created']);
			
			//pr($task);
			
			//$sum = $this->Support->findAll(null, "sum(points) as sum_price");
			

			
			//タスクの応援ポイント(Support.points)の合計を用意
			$condition = array(
				'conditions' => array('Support.task_id =' => $task['Task']['id']),
				'fields' => array('sum(points) as "sum_point"'),
				//'order' => array('Model.Client'),
				//'group' => array('Model.Client', 'Model.Job', 'Model.Description')
			);
			$sum = $this->Support->find('all', $condition);
			$points = $sum['0']['0']['sum_point'];
			//pr($sum['0']['0']['sum_point']);
			
			/*
			//pr($tasks[$i]['Support']);
			$points = 0;
			//unset($points);
			foreach($tasks[$i]['Support'] as $support){
				//pr($support['points']);
				$points += $support['points'];
			}
			//pr($points);
			//pr('<hr />');
			*/
			
			//軽量化(そこまで意味ある？)のために応援ポイント(Support.points)が無いタスクには追加しない
			if($points > 0){ $tasks[$i]['Task']['supporter_sum_points'] = $points; }
			
			
			//var_dump($tasks[$i]);
			//.json .rss用にいらないデータを削る
			if(isset($tasks[$i]['User'])){
				
				unset($tasks[$i]['User']['password']);
				unset($tasks[$i]['User']['twitter_user']);
				unset($tasks[$i]['User']['twitter_password']);
				unset($tasks[$i]['User']['oauth_token']);
				unset($tasks[$i]['User']['oauth_token_secret']);
				unset($tasks[$i]['User']['email']);
				unset($tasks[$i]['User']['token']);
				
			}
			
			//コメントで次のページが必要か件数をチェック
				//pr($this->Task->hasMany['Support']['limit']);
				//pr('<hr />');
				//pr($tasks[$i]['Task']['task']);
				//pr( '初期表示数:' . count($tasks[$i]['Support']) );
			//limitと同じ件数あるなら、次のコメントもある可能性があるのでチェック!
			if( count($tasks[$i]['Support']) === $this->Task->hasMany['Support']['limit'] ){
				//pr('limit一杯コメントがあるから、次のコメントもある可能性があるよ!');
				
				//pr('コメント数がチェック済みかどうか:' . !isset(nextCheck[$tasks[$i]['Task']['id']]));
				//同じtask_idがリストにあって、すでにコメント数がチェック済みならfindしない
				if( !isset($nextCheck[$tasks[$i]['Task']['id']]) ){
				
					$count = $this->Support->find('count',
														array(
																'fields' => 'Support.id', 
																//'order' => 'Support.comment_modified desc',
																'conditions' => array('task_id'=>$tasks[$i]['Task']['id'],'or' => array('comment !=' => '','supporter_user_id =' => $this->Auth->user('id'))), 
												));
					//pr('findのカウント数:' . $count);
					
					//同じtask_idの場合はfindしないためのチェック済み配列を用意する。
					//$check[$tasks[$i]['Task']['id']]['check'] = TRUE;
					
					//同じtask_idの場合はfindしないためのnext(次のコメント)チェック済み配列を用意する。
					if($count > $this->Task->hasMany['Support']['limit']){
						$nextCheck[$tasks[$i]['Task']['id']] = TRUE;
					}else{
						$nextCheck[$tasks[$i]['Task']['id']] = FALSE;
					}
				}
				
				
				
				//すでにコメント数がチェック済みで$countが無いか、limitより多ければ「次を表示ボタン」用のフラグを立てる
				if( $nextCheck[$tasks[$i]['Task']['id']] === TRUE || $count > $this->Task->hasMany['Support']['limit']){
					
					//pr('次へボタンを表示するよ!');
					
					//$tasksへネクスト表示フラグを立てる
					$tasks[$i]['NEXT'] = TRUE;
					
					
				}
				//ループで再利用するので破棄
				//unset($count);
				$count = 0;

				
			}
		
			++$i;
		}
		
		//pr($next);
		
		//pr($tasks);
		//$this->_test();
		
		//メインのタスク表示用にセット
		$this->set('tasks', $tasks);
		
		//試験的?
		//page:の値があればセットする、linkで並び替えリンクを実現するため(もっとシンプルな方法あるんじゃない?)
		/*
		if(isset($this->params['named']['page'])){
			$this->set('now_page', $this->params['named']['page']);
		}
		*/
		
		
		//各ページのタイトル
		//pr($this->viewVars['url_params']);
		if($this->viewVars['url_params'] === 'home'){
			$this->pageTitle = 'Listter / ホーム';
		}elseif($this->viewVars['url_params'] === 'home/timeline'){
			$this->pageTitle = 'Listter / タイムライン';
		}elseif($this->viewVars['url_params'] === 'public_timeline'){
			$this->pageTitle = 'Listter / 公開中のみんなのねがい';
		}else{
			$name = $this->viewVars['page_user']['User']['realname'] ? "{$this->viewVars['page_user']['User']['realname']} ({$this->viewVars['page_user']['User']['username']})" : $this->viewVars['page_user']['User']['username'] ;
			$this->pageTitle = "{$name} on Listter";
		}
	}

	/*
	function _test(){
		echo "test!";
	}
	*/

	function view() {
		//pr($this->params['timeline_id']);
		
		if(isset($this->params['timeline_id'])){
			// /:url_user/mitinori/:timeline_id の時は
			
			$ret = $this->Timeline->findById( $this->params['timeline_id'] );
			//pr($ret);
			$id = $ret['Timeline']['task_id'];
			
			//side_menuのリンク用にtimeline_idを用意、みちのり表示用の判別にも使う
			$this->set('timeline_id', $this->params['timeline_id'] );
			
			$cond = array('task_id' => $id,'Timeline.id' => $this->params['timeline_id'] );
		}else{
			// /:url_user/negai/:task_id の時は
			
			$id = $this->params['task_id'] ;
			$cond = array('task_id' => $id);
		}
		
		if ( !$id ) {
			//$this->Session->setFlash(__('Invalid Task.', true));
			
			//usrl_userがあってればそのユーザのページに飛ばすべきかも
			//$this->redirect('/');
			//pr($this->params['username']);
			//exit;
			$this->redirect(array('controller' => $this->params['url_user'] ));
		}
		
		// url_paramsを用意してside naviにユーザ情報を渡す
		$this->set('url_params', "{$this->params['controller']}/{$this->params['action']}" );
		
		//pr( $this->Task->find('all', array('conditions' => array('id' => $id ),'recursive' => 0,'fields' => 'user_id' ) ) );
		//$this->set('url_user', "{$this->params['controller']}/{$this->params['action']}" );
		
		
		$this->Task->id = $id; //field用にidをセット
		$user_id = $this->Task->field('user_id'); // $id の user_id が出力されます
		$this->User->id = $user_id;
		$this->set('url_user', $user_name = $this->User->field('username') ); // $user_id の username が出力されます
		
		//このページのユーザのデータをset
		$user = $this->User->findByUsername($user_name);
		$this->set('page_user', $user);
		
		//titleを設定
		$name = $user['User']['realname'] ? "{$user['User']['realname']} ({$user['User']['username']})" : $user['User']['username'] ;
		$this->pageTitle = "{$name} on Listter";
		
		
		/*
		if($this->Auth->user('id') !== $user_id){
			$colum = array('following'=>'user_id','follower'=>'follow_user_id');
		}else{
			$colum = array('following'=>'user_id','follower'=>'follow_user_id');
			//$colum = array('following'=>'follow_user_id','follower'=>'user_id');
		}
		*/
		
		//stats用のデータを用意		
		//フォローしている(folloing)・されている(follower)数をカウント
		$stats['folloing'] = $this->Follow->find('count', array('conditions' => array( 'user_id' => $user_id)) );
		//pr($stats['folloing']);
		$stats['follower'] = $this->Follow->find('count', array('conditions' => array( 'follow_user_id' => $user_id)) );
		//pr($stats['follower']);
		//願いの更新数
		$stats['timeline_count'] = $this->Timeline->find('count', array('conditions' => array('user_id' => $user_id)) );
		//pr($stats['timeline_count']);
		$this->set('stats', $stats);
	
		//var_dump($this->params['task_id']);
		
		/*
		$this->paginate=array(
			//'conditions' => array("user_id =" => "{$user['User']['id']}"),
			'conditions' => array('id' => $id),
			//'fields' => array(''),
			//'group' => array('Timeline.task_id'),
			//'page' => int(数値,最初に表示するページ。デフォルトは1,'last'(小文字)も可*1),
			'limit' => 20, //int(数値：showでも可。デフォルトは20)
			//'order' => $order,
			//'order' => array( 'task.modified' => 'desc', ),
			//'sort' => string(ソートkey：order でもよい。重なった場合はsortが優先される。),
			//'direction' => string(asc or desc:デフォルトはasc)
			//'recursive' => findAllに与える。
		);
		$task = $this->paginate();
		//pr($task);
		//pr($this->Task->read(null, $id));
		*/
		
		
		$this->paginate=array(
			//'conditions' => array("user_id =" => "{$user['User']['id']}"),
			//'conditions' => array('task_id' => $id),
			'conditions' => $cond,
			//'fields' => array(''),
			//'group' => array('Timeline.task_id'),
			//'page' => int(数値,最初に表示するページ。デフォルトは1,'last'(小文字)も可*1),
			'limit' => 20, //int(数値：showでも可。デフォルトは20)
			'order' => array('Timeline.modified' => 'desc'),
			/* 'order' => array(
				 	'task.modified' => 'desc',
				 	//'Timeline.modified' => 'asc',
				 ) */
			//'sort' => string(ソートkey：order でもよい。重なった場合はsortが優先される。),
			//'direction' => string(asc or desc:デフォルトはasc)
			//'recursive' => findAllに与える。
		);
		//pr($this->paginate('Timeline'));
		
		$this->Task->unbindModel(array('hasMany'=>array('Timeline')),false); //task情報だけ欲しいのでunbind
		if( !$task = $this->Task->read(null, $id) ){ //$idからタスクを習得
			//usrl_userがあってればそのユーザのページに飛ばすべきかも
			//$this->redirect('/'); //ありもしないID指定はそもそもおかしいので、取得できなかったらトップへ
			//pr($this->params['url_user']);
			$this->redirect(array('controller' => $this->params['url_user'] ));
		}
		//pr($task);
		
		$this->Timeline->unbindModel(array('belongsTo'=>array('Task')),false); //timeline情報だけ欲しいのでunbind
		$task['Timeline'] = $this->paginate('Timeline');
		
		//pr($task);
		
		$i=0;
		foreach($task['Timeline'] as $timeline){
			//modifiedをタイムスタンプにし、現在のタイムスタンプから引く、経過タイムスタンプを取得ご経過時間を返す関数
			$task['Timeline'][$i]['Timeline']['modified_elapsed'] = $this->Task->strToElapsed($timeline['Timeline']['modified']);
			$task['Task']['created_elapsed'] = $this->Task->strToElapsed($task['Task']['created']);
		
			++$i;
		}
		//pr($task);
		
		$this->set('task', $task);
		
		//pr($this);
	}

	function add() {
		//$this->Task->create();
		
		
		
		//バリデーションするためにセットが必要をするためにsetが必要(対象modelごとにする)
		//$this->Task->set($this->data);
		//$this->Timeline->set($this->data);
		
		
		/*
		$this->Timeline->validates();
		
		
		//普通にここの値を使ってエラー独自表示させたほうが速い
		$tl_error = $this->Timeline->invalidFields();
		//var_dump($tl_error);
		$task_error = $this->Task->invalidFields();
		//var_dump($task_error);
		if(count($task_error) === 0){
			$this->set('error', $tl_error);
		}
		*/


		
		//$this->Task->create();
		//var_dump($user = $this->Auth->user());
		//$this->log("Something didn't work!");
		
		//var_dump(count($this->data));
		if(count($this->data) === 0){
			//$this->redirect(array('controller'=>'home'));
			return;
			//$this->Task->render();
		}
		
		//taskにuser_idを付けてsaveする
		$data = $this->data;
		$data['Task']['user_id'] = $this->Auth->user('id');
		
		//pr($data);
		
		//timelineの最新フラグであるnewerを1に設定
		$data['Timeline'][0]['newer'] = 1;
		
		//行頭と行末の全角と半角を削除 //security componentにひっかかるので廃止
		//$data[Task][task] = trim($data[Task][task],'　');
		//$data[Timeline][0][comment] = trim($data[Timeline][0][comment],'　');
		
		
		
		if ($this->Task->saveAll($data,array('validate'=>'first'))) {
			
			//twitter投稿部分
			$this->User->unbindModel(array('hasMany'=>array('Task')),false); //twitter投稿用のuser情報だけ欲しいのでunbind
			$user = $this->User->findById($data['Task']['user_id']);
			
			if($user['User']['twitter_enabled']){   //twitterにつぶやく設定(1)だったらつぶやく
				//pr($this->Task->getInsertID());
				//最後のインサートしたtaskのIDを取得
				$task_id = $this->Task->getInsertID();
				
				//ドメインを含んだ絶対URLの取得
				App::import('Helper', 'Html');
				$html = new HtmlHelper();
				$base_url = $html->url(array('controller'=>'tasks','action'=>'view','task_id'=>"$task_id",'url_user'=>$this->Auth->user('username'),'page'=>1),true);
				
				$status = "「 {$data['Task']['task']} 」をはじめた、{$data['Timeline']['0']['progress']}% 今は:[{$data['Timeline']['0']['comment']}] {$base_url}";
				
				//twitter投稿用に文字数オーバーの場合は削る
				//pr($status);
				/*
				if( ($count = mb_strlen($status, Configure::read('App.encoding'))) > 140 ){    //140文字以上はtwitter用にダイエット
					//pr($count);
					$count = $count - 138;   //文末に..を追加するので二文字減らして138文字
					//pr($count);
					$count = mb_strlen($data['Timeline']['0']['comment'], Configure::read('App.encoding')) - $count;
					//pr($count);
					//$min_task = mb_strimwidth($this->viewVars['tasks']["$task_id"],'0',$count,'..',Configure::read('App.encoding'));
					$min_comment = mb_substr($data['Timeline']['0']['comment'],'0',$count,Configure::read('App.encoding'));
					//$status = "「 {$data['Task']['task']} 」をはじめた、{$data['Timeline']['0']['progress']}% 今は:[{$min_comment}..] {$base_url}";
					$status = "「 {$data['Task']['task']} 」をはじめた、{$data['Timeline']['0']['progress']}% 今は:[{$min_comment}…] {$base_url}";
					//pr($status);
					//pr(mb_strlen($status, Configure::read('App.encoding') ));
				}
				*/
				
				//$statusが140文字以上ならtaskとtimelineをまとめて配列を受けて短くする
				if( $twitter = $this->MyTool->twitter_post_trim( $status , $data['Task']['task'] , $data['Timeline']['0']['comment']) ){
					$status = "「 {$twitter['min_task']} 」をはじめた、{$data['Timeline']['0']['progress']}% 今は:[{$twitter['min_comment']}] {$base_url}";
					//var_dump($status);
				}
				
				$check = $this->OauthTwitter->post( $status ,$user['User']['oauth_token'] , $user['User']['oauth_token_secret'] );
				//pr($check);
				
			/*	
				//$this->PostTwitter->post("達成率{$data['Timeline']['0']['progress']}% 現在:[{$data['Timeline']['0']['comment']}]、目標は「{$data['Task']['task']}」{$base_url}{$task_id}" );
				$this->PostTwitter->post("ねがい「 {$data['Task']['task']} 」をはじめた、{$data['Timeline']['0']['progress']}% 今は:[ {$data['Timeline']['0']['comment']} ] {$base_url}" );
				//pr($this->PostTwitter->post("test"));
			*/
			}
			
			//$this->Session->setFlash(__('The Task has been saved' , true));
			$this->Session->setFlash(__('新しいねがいを始めました、大事に育ててくださいね' , true));
			$this->redirect(array('controller'=>'home'));
		} else {
			$this->Session->setFlash(__('新しいねがいへの一歩を踏み出しましょう', true));
		}
	
		//pr($this);
	}

	/*
	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid Task', true));
			$this->redirect(array('action'=>'index'));
		}
		if (!empty($this->data)) {
			if ($this->Task->save($this->data)) {
				$this->Session->setFlash(__('The Task has been saved', true));
				$this->redirect(array('action'=>'index'));
			} else {
				$this->Session->setFlash(__('The Task could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Task->read(null, $id);
		}
	}
	*/

	function delete() {
		
		//var_dump($this->params['task_id']);
		$id = $this->params['task_id'];
	
		if (!$id || !ctype_digit($id)) {
			$this->Session->setFlash(__('Invalid id for Task', true));
			$this->redirect('/home');
		}
		
		
		//$no = $id;
		$tasks = $this->Task->find('all' , 
					 array(
						'fields'=>'Task.task,Task.user_id',
						'conditions' => array('Task.id' => $id),
						//'recursive' => 1
						//'conditions' => array('Task.id' => 7)
					)
			);
		//var_dump($tasks);
		//if(!$tasks){
			//$this->redirect('/');
		//}
		
		//取得したtaskのuser_idとauthでログインしているユーザidが違ったら不正アクセスなのでトップに飛ばす
		if($this->Auth->user('id') !== $tasks[0]['Task']['user_id']){
			//$this->Session->setFlash(__('!!!!!!!!', true));
			$this->redirect('/home');
		}
		
		
		if ($this->Task->del($id)) {
			//$this->Session->setFlash(__('Task deleted', true));
			$this->Session->setFlash(__('ねがいを削除しました', true));
			$this->redirect('/home');
		}
	}
	
	function completed() {
		
		//var_dump($this->params['task_id']);
		$id = $this->params['task_id'];
		$flag = $this->params['complete_flag'];
	
		if ( !$id || !ctype_digit($id) ) {
			$this->Session->setFlash(__('Invalid id for Task', true));
			$this->redirect('/home');
		}
		
		
		//$no = $id;
		$tasks = $this->Task->find('all' , 
					 array(
						'fields'=>'Task.task,Task.user_id,Task.completed',
						'conditions' => array('Task.id' => $id),
						//'recursive' => 1
						//'conditions' => array('Task.id' => 7)
					)
			);
		//var_dump($tasks);
		//if(!$tasks){
			//$this->redirect('/');
		//}
		
		//取得したtaskのuser_idとauthでログインしているユーザidが違ったら不正アクセスなのでトップに飛ばす
		if($this->Auth->user('id') !== $tasks[0]['Task']['user_id']){
			//$this->Session->setFlash(__('!!!!!!!!', true));
			$this->redirect('/home');
		}
		
		$this->Task->set("id",$id);
		if( $this->Task->saveField('completed', $flag ) ){
			if($flag){
				$this->Session->setFlash(__('ねがいが殿堂入りしました。おめでとうございます！', true));
			}else{
				$this->Session->setFlash(__('ねがいの殿堂入りを解除しました。', true));
			}
			$this->redirect('/home');
		}
		
		/*
		if ($this->Task->del($id)) {
			//$this->Session->setFlash(__('Task deleted', true));
			$this->Session->setFlash(__('ねがいを削除しました', true));
			$this->redirect('/home');
		}
		*/
		
	}
	
	function top() {
		$this->pageTitle = 'Listter - ねがいをそだてる';
		
		//cookie component の設定
		$this->Cookie->name = 'tmp_listter';
		$this->Cookie->key = '~SI2@2()qVs*&sXOw!a28.<';
		

		
		/* 自動ログイン処理 */
		//$this->Auth->loginRedirect = false;
		//$this->Auth->loginRedirect = array('controller'=>'home');
		//$this->Auth->autoRedirect = false; // loginメソッド内を実行させるのに必要
		
		
		$cookie = $this->Cookie->read('tmp_cache'); //tmp_cacheという名前はダミーで実際はログイン認証情報
		//var_dump($cookie);
		if (!is_null($cookie)) {
			//echo 'login!可能';
			//クッキーの情報でログインしてみる。
			//if ($this->Auth->login($cookie)) {
				//$this->redirect($this->Auth->redirect('/'));
				//$this->redirect($this->Auth->redirect());
				//$this->redirect(array('controller'=>'home'));
				//$this->redirect(array('controller' => 'tasks', 'action' => 'index','url_user' => 'home'));
				//echo "リダイレクト!";
				//$this->redirect(array('controller' => 'users', 'action' => 'login'));
				//$this->redirect(array('controller' => 'tasks', 'action' => 'index','page'=>1,'sort'=>'Timeline.modified','direction'=>'desc','url_user'=>'home'));
			//}
			
			//topの「自動ログイン」表示フラグ
			$this->set('auto_login', "true");
		}
		
		
		
		
		

		//トップページでログインしていたら、/homeへリダイレクト
		if( $this->Auth->user() ){
			$this->redirect('/home');
		}
		
		$this->set('url_params', '/');
		
	}

}
?>