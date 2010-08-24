<?php
class FollowsController extends AppController {

	var $name = 'Follows';
	var $helpers = array('Html', 'Form',);
	var $components = array('Auth','Qdmail','QdmailWrap'); //Securityコンポーネントはいらないんじゃね、なので削った
	var $uses = array('Follow','User','Timeline');
	
	
	function beforeFilter() {
		//Configure::write('debug',0); //ajaxのためにここでdebug:0にしないと余計な表示が出る(debug:1以上の時に)
		
		//Authコンポーネント関連
		//beforeFilterにログインしなくても良いactionを指定(カンマ区切りで複数指定)
		$this->Auth->allow('follow_list'); //フォローリストはログインして無くても閲覧可
		$this->set('login', $this->Auth->user()); //loginしているかどうかview用(elementで右上のバーの内容を変化させてる)
		//var_dump($this->Auth->user());
	}

	/*
	function index() {
		$this->Follow->recursive = 0;
		$this->set('follows', $this->paginate());
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid Follow.', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->set('follow', $this->Follow->read(null, $id));
	}
	*/

	function add() {
		//$this->view = "View";  //qdmailとdebug kitを併用するとエラー起きるのでその対策、本番環境では当然いらない
		//Configure::write('debug',0);
		$this->layout = 'ajax';
		
		// ログインしていない、または自分のuser_idをフォローしようとしていたらjsonに何もセットせず、結果的にjasonでnullのみを返す
		if(($id = $this->Auth->user('id')) === null || $this->Auth->user('id') === $this->params['follow_id'] ){
			//$this->cakeError('error404');

			//$this->redirect($this->Auth->logout());
		}else{
			$res['login'] = TRUE; //ちゃんとloginしている場合はjsonファイルに設定、というかここがtrueだと処理成功とjsonで判断される
		}
		
		//var_dump($this->Auth->user('id'));
		
		$data['Follow'] = array('user_id'=> $id, 'follow_user_id'=> $this->params['follow_id'] );
		
		//var_dump($data);
		//$this->data = $data;
		
		$this->Follow->create();
		if ($this->Follow->save($data)) {
			//$this->Session->setFlash(__('The Follow has been saved', true));
			
			//メールの送信準備
			$this->User->unbindModel(array('hasMany'=>array('Task')),false); //twitter投稿用のuser情報だけ欲しいのでunbind
			$user = $this->User->findAllById( $this->params['follow_id'] );
			//pr($user);
			
			if($user[0]['User']['email'] && $user[0]['User']['follow_mail_enabled']){
				//followした人の名前情報
				$following_name = $this->Auth->user('realname') ? $this->Auth->user('realname') : $this->Auth->user('username') ;
				//followした人の詳しい名前情報
				$following_2name = $this->Auth->user('realname') ? "{$this->Auth->user('realname')} ({$this->Auth->user('username')})" : $this->Auth->user('username') ;
				
				//followされた人の名前情報
				//$follower_name = $user[0]['User']['realname'] ? $user[0]['User']['realname'] : $user[0]['User']['username'] ;
				//followされた人の詳しい名前情報
				$follower_2name = $user[0]['User']['realname'] ? "{$user[0]['User']['realname']} ({$user[0]['User']['username']})" : $user[0]['User']['username'] ;
				//ドメインを含んだ絶対URLの取得
				App::import('Helper', 'Html');
				$html = new HtmlHelper();
				$base_url = $html->url(array('controller'=> $this->Auth->user('username') ),true);
				
				//$res = array($user,$follow_username,$follow_realname,$following_username,$user[0]['User']['email'],);
				
				
				//メールの送信
				$this->QdmailWrap->address = $user[0]['User']['email'];
				$this->QdmailWrap->subject = "{$following_name}があなたをフォローし始めました";
				$this->QdmailWrap->message = "こんにちは、{$follower_2name}さん。\n\n{$following_2name} があなたをフォローし始めました。\n\n{$following_name}さんのプロフィールはこちらまで：\n  {$base_url}\n\n{$following_name}をフォローするために、「フォロー」のボタンをクリックしてください\nりすったー。\n \n--\nリスッターから「follow notification」のメールを受信したくない場合は、今すぐ解除できます。リスッターからのメール選択について再度登録や変更をしたい場合は、自分のアカウントから「設定」へ行きお知らせ機能を操作してください。";
				
				if( $this->QdmailWrap->post() ){
					//メール送信に成功したら
				}
			}
			
			
		}
		
		//$res['test']='testdayo';
		$this->set('result', json_encode($res));
		$this->render(null, null, VIEWS . DS . 'ajax.ctp'); // Ajax 用共通 view
	}

	/*
	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid Follow', true));
			$this->redirect(array('action'=>'index'));
		}
		if (!empty($this->data)) {
			if ($this->Follow->save($this->data)) {
				$this->Session->setFlash(__('The Follow has been saved', true));
				$this->redirect(array('action'=>'index'));
			} else {
				$this->Session->setFlash(__('The Follow could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Follow->read(null, $id);
		}
		$users = $this->Follow->User->find('list');
		$this->set(compact('users'));
	}
	*/
	
	function follow_list() {
		//基本followsコントローラはajax用なのでdebug off
		//Configure::write('debug',3);
		
		//pr($this->params['url_user']);
		//pr($this->Auth->user());
		
		// url_userがsetされてなくて(つまり /follwing or /follower)で、ログインしてなかったらリダイレクト
		if( !isset($this->params['url_user']) && !$this->Auth->user() ){
			//$this->redirect('/');
			$this->redirect(array('controller' => 'users','action' => 'login'));
		}
		
		//pr($this->params['followers']);
		
		// /:url_user/ だった場合はurl_userからuser_idを取得
		if(isset($this->params['url_user'])){
			//pr($this->User->findByUsername($this->params['url_user']));
			
			$this->User->unbindModel(array('hasMany'=>array('Task')),false); //twitter投稿用のuser情報だけ欲しいのでunbind
			if( ($user = $this->User->findByUsername($this->params['url_user'])) === false ){ 
				$this->redirect('/'); //指定されたユーザネームがなければ'/'へ
			}
			//pr($user);
			$url_username = $user['User']['username'];  //表示(vars)用にurlのユーザ名を用意
		}
		
		// url_userから得たuser_idがあればそれで、無ければログインのuser_idをセット
		$user_id = isset($user['User']['id']) ? $user['User']['id'] : $this->Auth->user('id') ;
		
		
		if( isset($this->params['following']) ){   //  /following だったら このユーザ(user_id)がフォローしてるのは
			$title_after = 'がフォローしているユーザー';  //htmlのtitleの後半を設定
			$cond = array( 'user_id =' => $user_id );
			$type = 'following';  //set用に処理方法を用意
		}elseif( isset($this->params['followers']) ){   //  /followers だったら このユーザ(user_id)をフォローしてるのは
			$title_after = 'をフォローしているユーザー';  //htmlのtitleの後半を設定
			$this->Follow->bindModel(
						array(
							'belongsTo'=>array('User'=>array(
										//'limit'=> 1,
										//'order' => 'Timeline.modified desc',
										//'conditions' => array('Timeline.id =' => 'Task.new_tl_id'),
										//'group' => array('modified'), //fields to GROUP BY
										'foreignKey' => 'user_id',
															) )),false
			);
			
			$cond = array( 'follow_user_id =' => $user_id );
			$type = 'followers';  //set用に処理方法を用意
		}
		
		$this->paginate=array(
				'conditions' => $cond ,
				'limit' => 20, //int(数値：showでも可。デフォルトは20)
				'order' => array( 'Follow.created' => 'desc', ),
		);
		
		$this->Follow->recursive = 0;
		$this->set('follows', $this->paginate());
		
		//表示用の値をいくつか用意
		if(isset($url_username)){ 
			$option_vars['url_username'] = $url_username ;   //urlにユーザ指定がある場合はset用に名前を渡す
		}else{
			$url_username = $this->Auth->user('username');
		}
		$option_vars['type'] = $type;  // followers または following
		$this->set('vars', $option_vars);
		
		
		$user = $this->User->findByUsername($url_username);
		$this->set('page_user', $user);
		$this->set('url_user', $user['User']['username']);
		
		//titleを設定
		$this->pageTitle = $user['User']['username'] . $title_after ; //htmlのタイトルを設定
		
		//stats用のデータを用意	
		//フォローしている(folloing)・されている(follower)数をカウント
		$stats['folloing'] = $this->Follow->find('count', array('conditions' => array('user_id' => $user_id)) );
		//pr($stats['folloing']);
		$stats['follower'] = $this->Follow->find('count', array('conditions' => array('follow_user_id' => $user_id)) );
		//pr($stats['follower']);
		//願いの更新数
		$stats['timeline_count'] = $this->Timeline->find('count', array('conditions' => array('user_id' => $user_id)) );
		//pr($stats['timeline_count']);
		$this->set('stats', $stats);
		
	}

	function delete($id = null) {
		$this->layout = 'ajax';
		//$idの値はroutes.phpで数字のみに値制限(チェック)している
		//var_dump($this->params['follow_id']);
		
		if(($id = $this->Auth->user('id')) === null){
			//$this->cakeError('error404');

			//$this->redirect($this->Auth->logout());
		}else{
			$res['login'] = TRUE; //ちゃんとloginしている場合はjsonファイルに設定
		}
		
		$conditions = array('Follow.follow_user_id'=>"{$this->params['follow_id']}",'Follow.user_id'=>"{$this->Auth->user('id')}");
		if($this->Follow->deleteAll($conditions)) {
			//$this->Session->setFlash('削除しました');
		} else {
			//$this->Session->setFlash('削除に失敗しました');
		}
		
		/*
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for Follow', true));
			$this->redirect(array('action'=>'index'));
		}
		
		if ($this->Follow->del($id)) {
			$this->Session->setFlash(__('Follow deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		*/
		$this->set('result', json_encode($res));
		$this->render(null, null, VIEWS . DS . 'ajax.ctp'); // Ajax 用共通 view
	}

}
?>