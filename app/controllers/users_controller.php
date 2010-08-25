<?php
class UsersController extends AppController {

	var $name = 'Users';
	var $helpers = array('Html', 'Form','Javascript');
	//AuthComponentの宣言
	var $components = array('Auth', 'Qdmail','Session','Security', 'Cookie', 'QdmailWrap','OauthTwitter');
	//var $uses = array('User');

	
	//beforeFilterにログインしなくても良いactionを指定(カンマ区切りで複数指定)
	function beforeFilter() {
		$this->pageTitle = 'Listter'; //標準のタイトル
	
		//cookie component の設定
		$this->Cookie->name = 'tmp_listter';
		$this->Cookie->key = '~SI2@2()qVs*&sXOw!a28.<';
		//parent::beforeFilter();  // /app/app_controller.php の beforeFilterを使用する
		
		//echo $this->Cookie->read('autoLogin');
		//$this->AutoLogin->expires = '+1 month';
	
		//navi用のelementでログイン判断用にセット
		$this->set('login', $this->Auth->user() ? TRUE : FALSE );
	
		$this->Auth->allow('add','capcha_img','recent_password','reset_password');
		//$this->Auth->loginRedirect  = '/tasks/index/';
		$this->Auth->loginRedirect = array('controller'=>'home');
		$this->Auth->loginError = __('パスワードもしくはユーザ名をご確認下さい。', true);
		
	
		$this->Auth->autoRedirect = false; // loginメソッド内を実行させるのに必要
		//$this->Auth->loginRedirect  = '/' . $this->Auth->user('username');
		
		//var_dump($this->action);
		//var_dump($this->Auth->user());
		
		//var_dump($this->session->read('Auth.User'));
		//var_dump($this->Auth);
		
		/*
		if($this->Cookie->read('autoLogin')){
			$this->redirect(array('controller'=>'login'));
		}
		*/
		
		// addまたはloginアクションは、ログイン中には実行できない
		if( ($this->action === 'add' || $this->action === 'login' || $this->action === 'recent_password') && $this->Auth->user() ){
		//if( ($this->action === 'add' || $this->action === 'login') && ( $this->Auth->user() || $this->Cookie->read('autoLogin') ) ){
			//print 'login!';
			//$this->Session->setFlash(__('現在ログイン中です。', true));
			$this->redirect(array('controller'=>'home'));
		}
//test
		
		
		//設定画面用に現在のControllerとactionを用意する。
		//pr($this->action);
		$setting_aciton = array('password','twitter','settings','notifications'); //設定画面のアクションを配列用意してチェック
		if( in_array($this->action,$setting_aciton) ){
			//print "setting!";
			$this->set('action', $this->action);
		}
	}

	
	
	/**
	*  AuthComponent がログインに必要な機能を提供します。
	*  そのため、この関数の中身は空にしておいてください。
	*/
	function login() {
		//$this->pageTitle = 'Listter / login';
		
		//logout後に/tasks/addされるとなぜかなにも$dataが無いのに投稿可能だったので対処
		/*
		$this->log( $this->session->read('Auth.redirect') );
		if($this->session->read('Auth.redirect') === '/tasks/add'){
			$this->session->del('Auth.redirect');
			//$this->log( 'Auth.redirect is delete!' );
		}
		*/
		//$this->log( $this->session->read('Auth.redirect') );
		/*
		if ($this->Auth->user()) {      //beforeFilterで $this->Auth->autoRedirect = false; してるので手動でリダイレクト
			$this->redirect(array('controller'=>'home'));
		}
		*/
		
		/* 自動ログイン処理 */
		if (empty($this->data)) {
			//$cookie = $this->Cookie->read('Auth.User');
			$cookie = $this->Cookie->read('tmp_cache'); //tmp_cacheという名前はダミーで実際はログイン認証情報
			if (!is_null($cookie)) {
				//クッキーの情報でログインしてみる。
				if ($this->Auth->login($cookie)) {
					//var_dump($this->Auth->redirect());
					if( $this->Auth->redirect() ==="/"){
						//var_dump($this->Auth->redirect());
						$this->redirect($this->Auth->redirect());
						//$this->redirect('/home');
					}else{
						$this->redirect($this->Auth->redirect());
					}
					//$this->redirect(array('controller'=>'home'));
				}
			}
		}
	
		if ($this->Auth->user()) {
			if (!empty($this->data['User']['remember_me'])) {
				$cookie = array();
				$cookie['username'] = $this->data['User']['username'];
				$cookie['password'] = $this->data['User']['password'];//ハッシュ化されている
				//$this->Cookie->write('Auth.User', $cookie, true, '+2 weeks');//3つめの'true'で暗号化
				$this->Cookie->write('tmp_cache', $cookie, true, '+2 weeks');//3つめの'true'で暗号化
				unset($this->data['User']['remember_me']);
			}
			$this->redirect($this->Auth->redirect());
		}
		/* 自動ログイン処理 END */

	}

	function logout() {
		//$this->Cookie->del('Auth.User');
		$this->Cookie->del('tmp_cache');
		
		//$this->Session->del('Auth.User');
		$this->redirect($this->Auth->logout());
	}
	
	function capcha_img() {
	
		// 画像を作成します
		$im = imagecreatetruecolor(250, 50);
		$bg_color = imagecolorallocate($im, 0xFF, 0xFF, 0xFF);
		$black = imagecolorallocate($im, 0x00, 0x00, 0x00);

		// 背景を赤にします
		imagefilledrectangle($im, 0, 0, 299, 99, $bg_color);

		// ttf フォントファイルへのパスを設定します
		//$font_file = VENDORS  . 'ipam.ttf';
		//$font_file = VENDORS  . '21k.ttf';
		$font_file = APP . 'vendors/' . '21k.ttf';
		//var_dump($font_file);
		
		//var_dump(VENDORS .  'ipam.ttf');
		$font_size = 20;
		$vertical = 8;
		$horizontal = 35;

		//間違えやすい文字は変えておく
		$txt = base64_encode(mt_rand());
		$txt = str_pad("$txt",'8',"$txt");
		$txt = mb_strimwidth($txt, 0, 8);
		$txt = str_shuffle($txt);
		$txt = str_ireplace('o','8',$txt);
		$txt = str_replace('0','6',$txt);
		$txt = str_replace('=','3',$txt);
		$txt = str_replace('x','X',$txt);
		$txt = str_replace('k','K',$txt);
		$txt = str_replace('m','M',$txt);
		$txt = str_replace('w','W',$txt);
		$txt = str_replace('c','C',$txt);
		$txt = str_replace('z','Z',$txt);
		$txt = str_replace('g','G',$txt);
		$txt = str_replace('9','G',$txt);
		$txt = strtoupper($txt);	//captcha簡単にするために全部大文字に
		
		$this->Session->write('image_auth_string', $txt);
		
		$i = 0;
		while(strlen($txt) > $i){
			imagefttext($im, $font_size + mt_rand(-4,4), mt_rand(-25,30), $vertical, $horizontal + mt_rand(-5,5), $black, $font_file, $txt["$i"]);
			//imagefttext($im, 20, -20, 35, 55, $black, $font_file, $txt['2']);
			$i++;
			$vertical += $font_size + mt_rand(7,12);;
		}
		
		// 画像をブラウザに出力します
		header('Content-Type: image/png');

		imagepng($im);
		imagedestroy($im);
		
		
		//session_start();
		//$_SESSION['image_auth_string'] = $txt;
	}
	
	//ユーザ追加
	function add() {
		$this->pageTitle = 'Listter / アカウントを登録';
		//var_dump($this->session->read('Auth.User'));
	
		/* メール送信部分
		$param = array(
			'host'=>'ziro.jp',
			'port'=>'5190',
			'from'=>'ryota@listter.com',
			'user'=>'ryoziro',   //postmaster@example.com
			'pass' => 'y20now2411',
			'protocol'=>'SMTP_AUTH',
			);
		
		$this->Qdmail->to('ryota@heeha.ws','宛先様');
		$this->Qdmail->subject('メールのテスト');
		$this->Qdmail->from('ryota@listter.com','送り主');

		$this->Qdmail->smtp(true);
		$this->Qdmail->smtpServer($param);
		
		$this->Qdmail -> cakeText('本文'); //Cakephpのemailのlayoutを使用している。
		//$this->Qdmail -> text( '本文をここにかきます' );

		$fg=$this->Qdmail->send();
		//var_dump($fg);
		*/
		

		
		
		
		//var_dump($this->data['User']);
		//$this->User->set( $this->data );
		//$this->User->validates();
		//if (isset($this->data['User']['password_confirm']) && !empty($this->data)) {
		if (!empty($this->data)) {
			//modelでは(Authの)passwordメソッドが使えないからここで用意した値をmodelでバリデーション
			$this->data['User']['check_password_confirm'] = $this->Auth->password($this->data['User']['password_confirm']);
			$this->data['User']['check_capcha'] = $this->Session->read('image_auth_string');
			
			
			if($this->data['User']['email'] === ''){ $this->data['User']['email'] = null ; } //emailが空の時はdbにnullを入れる
			//var_dump($this->data['User']);
			
			//if ($this->data['User']['password'] == $this->Auth->password($this->data['User']['password_confirm'])) {
				$this->User->create();
				if ($this->User->save($this->data)) {
					//メール送信
					if( $this->data['User']['email'] !== null ) {
						//ドメインを含んだ絶対URLの取得
						App::import('Helper', 'Html');
						$html = new HtmlHelper();
						$base_url = $html->url(array('controller'=>'home',),true);
						//pr($base_url);
						
						$this->QdmailWrap->address = $this->data['User']['email'];
						$this->QdmailWrap->subject = 'lisstterにようこそ！';
						$this->QdmailWrap->message = "{$this->data['User']['username']}さん、listterにようこそ！\nまずはねがいをそだてましょう。。\n{$base_url}";
						$this->QdmailWrap->post();
					}
					
					//$this->Session->setFlash(__('The User has been saved', true));
					$this->Session->setFlash(__('ユーザー登録ありがとうございます。ログインをしてください。', true));
					$this->redirect(array('action'=>'login'));
				} else {
					
					$this->data['User']['password'] = null;
					//$this->Session->setFlash(__('再入力してください。', true));
					//var_dump($this->data);
				}
			//}
		}
	}



	/*
	function index() {
		//var_dump($this->Session->read('image_auth_string'));
		var_dump($this->Auth->user());
		$this->User->recursive = 0;
		$this->set('users', $this->paginate());
	}
	*/

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid User.', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->set('user', $this->User->read(null, $id));
	}

	function settings() {
		$this->pageTitle = 'Listter / ユーザー情報';
		$id = $this->Auth->user('id');
		
		if (!empty($this->data)) {
			//$id = $this->Auth->user('id');
			$this->data['User']['id'] = $id; //保存時に自分のidを指定
			
			if($this->data['User']['email'] === ''){ $this->data['User']['email'] = null ; } //emailが空の時はdbにnullを入れる
			
			//pr($this->data['User']['password']);
			
			if ($this->User->save($this->data)) {
				$this->Session->setFlash(__('ありがとう。設定を保存しました。', true));
				$this->redirect(array('controller' => 'users','action' => 'settings'));
			}
		}
		
		if (empty($this->data)) {
			$this->User->unbindModel(array('hasMany'=>array('Task')),false);  //taskの情報はいらないのでunbind
			$this->data = $this->User->read(null, $id);
		}
	}
	
	function notifications(){
		$this->pageTitle = 'Listter / お知らせ機能';
		$id = $this->Auth->user('id');
		
		if (!empty($this->data)) {
			//$id = $this->Auth->user('id');
			$this->data['User']['id'] = $id; //保存時に自分のidを指定
			
			//if($this->data['User']['email'] === ''){ $this->data['User']['email'] = null ; } //emailが空の時はdbにnullを入れる
			
			//pr($this->data['User']['password']);
			
			if ($this->User->save($this->data)) {
				$this->Session->setFlash(__('ありがとう。お知らせ機能の設定を保存しました。', true));
				$this->redirect(array('controller' => 'users','action' => 'notifications'));
			}
		}
		
		if (empty($this->data)) {
			$this->User->unbindModel(array('hasMany'=>array('Task')),false);  //taskの情報はいらないのでunbind
			$this->data = $this->User->read(null, $id);
		}
	}

	function password() {
		$this->pageTitle = 'Listter / パスワード';
		
		
		/*
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid User', true));
			$this->redirect(array('action'=>'index'));
		}
		*/
		
		
		
		if (!empty($this->data)) {
			//$id = $this->Auth->user('id');
			$this->data['User']['id'] = $this->Auth->user('id'); //保存時に自分のidを指定
			//modelでは(Authの)passwordメソッドが使えないからここで用意した値をmodelでバリデーション
			$this->data['User']['check_current_password'] = $this->Auth->password($this->data['User']['current_password']);
			$this->data['User']['check_password_confirm'] = $this->Auth->password($this->data['User']['password_confirm']);
			$this->data['User']['password'] = empty($this->data['User']['password']) ? $this->data['User']['password'] : $this->Auth->password($this->data['User']['password']);
			//$this->data['User']['password'] = $this->Auth->password($this->data['User']['password']);
			
			//pr($this->data['User']['password']);
			
			if ($this->User->save($this->data)) {
				//$this->Session->setFlash(__('The User has been saved', true));
				$this->Session->setFlash(__('パスワードを変更しました。', true));
				//print 'save!!';
				$this->redirect(array('controller' => 'users','action' => 'password'));
			} else {
				//どんなvalidatitonエラーが起きたか取得
				$this->User->set( $this->data );
				//pr($this->User->invalidFields());
				
				//$this->Session->setFlash(__('The User could not be saved. Please, try again.', true));
				
				if($this->data['User']['current_password'] === ''){
					$this->Session->setFlash(__('パスワードを変更するには現在のパスワードを入力してください。', true));
					$this->redirect(array('controller' => 'users','action' => 'password'));
				}elseif(array_key_exists('current_password', $this->User->invalidFields())){
					$this->Session->setFlash(__('入力したパスワードは正しくないようです。', true));
					$this->redirect(array('controller' => 'users','action' => 'password'));
				}elseif($this->data['User']['password_confirm'] !== $this->data['User']['password']){
					$this->Session->setFlash(__('新しいパスワードを確認してください。', true));
					//$this->redirect(array('controller' => 'users','action' => 'password'));
				}
				
				$this->data['User']['password'] = '';
				$this->data['User']['password_confirm'] = '';
			}
		}
		
		
		/*
		if (empty($this->data)) {
			//$this->data = $this->User->read(null, $id);
		}
		*/
	}

	/*
	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid User', true));
			$this->redirect(array('action'=>'index'));
		}
		if (!empty($this->data)) {
			if ($this->User->save($this->data)) {
				$this->Session->setFlash(__('The User has been saved', true));
				$this->redirect(array('action'=>'index'));
			} else {
				$this->Session->setFlash(__('The User could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->User->read(null, $id);
		}
	}
	*/
	
	function twitter() {
		//Configure::write('debug',3);
		$this->pageTitle = 'Listter / Twitter';
		
		//oauthの認証状態をチェックする
		$id = $this->Auth->user('id');
		$user = $this->User->find('all', array(
												'recursive' => 0, //int
												'fields' => array('oauth_token','oauth_token_secret'),
												'conditions' => array('id =' => $id,)
		));
		//pr($user);
		
		if( !isset($_GET['oauth_token']) && $user[0]['User']['oauth_token'] && $user[0]['User']['oauth_token_secret']){
			$check = $this->OauthTwitter->check( $user[0]['User']['oauth_token'] , $user[0]['User']['oauth_token_secret'] );
			//$check = $this->OauthTwitter->post( 'test' ,$user[0]['User']['oauth_token'] , $user[0]['User']['oauth_token_secret'] );
			//var_dump($check);
			//echo '認証ok';
			if($check){
				//echo 'oauthが機能中';
				$this->set('oauth_enabled',$check);
			}
		}
		
		if( !isset($check) || !$check){
			//pr('urlにoauth_token があるか　dbのtokenで認証不可');
			if( !isset($_GET['oauth_token']) ){
				//pr('twitterからのoauth_tokenが無い初期状態');
				
				//twitterから認証URLとリクエストtokenを得る
				$ret = $this->OauthTwitter->getAuthorizeURL();
				//pr($ret);
				
				//セッションにセット
				$this->Session->write('Oauth.request_token', $ret['oauth_token']);
				$this->Session->write('Oauth.request_token_secret', $ret['oauth_token_secret']);
				
				$this->set('request_link',$ret['request_link']);
				//$this->redirect($ret['request_link']);
			}elseif( $_GET['oauth_token'] ){
				//echo 'oauth_token get daze!!';
				$request_token = $this->Session->read('Oauth.request_token');
				$request_token_secret = $this->Session->read('Oauth.request_token_secret');
				//pr($request_token);
				//pr($request_token_secret);
				//if(){
					
				//}
				
				$ret = $this->OauthTwitter->getAccessToken( $request_token , $request_token_secret );
				//pr($ret);
				if(!$ret){
					//echo 'false';
					$this->redirect(array('controller' => 'users','action' => 'twitter'));
				}else{
				
					$this->set('oauth_enabled',$ret);
					
					//print_r($ret);
					//print_r($id);
					
					//token保存
					$tmp['User']['id'] = $id;
					$tmp['User']['oauth_token'] = $ret['oauth_token'];
					$tmp['User']['oauth_token_secret'] = $ret['oauth_token_secret'];
					//echo '<hr>';
					//print_r($this->data);
					//echo '<hr>';
					//echo 'test!!!!';
					if($test = $this->User->save($tmp)){
						//echo '<hr>';
						//print_r($test);
						//echo '<hr>';
						$this->Session->setFlash(__('Twitterの認証が成功しました。', true));
						//$this->redirect(array('controller' => 'users','action' => 'twitter'));
					}
				}
			}
		}
		
		/*
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid User', true));
			$this->redirect(array('action'=>'index'));
		}
		*/
		
		
		
		//ツイッター認証チェックで回数オーバーしたかチェック
		if( $this->Session->check('Twitter.stopped') ){
			if( $this->Session->read('Twitter.stopped') > time() ){ //もし認証制限中なら
				//print '制限中';
				$twitter_stopped = true; //saveしないフラグを立てる
				$this->Session->setFlash(__('Twitterの認証失敗が多いので、しばらく認証チェックが出来ません。', true));
				$this->set('twitter_stopped', true);  //view用に制限中のフラグをset
				//$this->redirect(array('controller'=>'home'));
			}else{
				//制限時間が終了していれば、チェックと制限のsession削除
				$this->Session->del('Twitter.stopped');
				$this->Session->del('Twitter.count');
			}
		}
		
		
		
		$id = $this->Auth->user('id');
		
		//dataがあって、$twitter_stoppedが定義されていなかったらsave
		if ( !empty($this->data) && !isset($twitter_stopped) ) {
			$this->data['User']['id'] = $id; //保存時に自分のidを指定
			if ($this->User->save($this->data)) {
				if($this->data['User']['twitter_enabled']){
					$this->Session->setFlash(__('Twitterにつぶやく設定にしました。世界に向けてねがいをイメージしてごらん', true));
				}else{
					$this->Session->setFlash(__('Twitterにつぶやかない設定にしました。', true));
				}
				
				$this->redirect(array('action'=>'twitter'));
			} else {
				$this->Session->setFlash(__('The User could not be saved. Please, try again.', true));
				
				//saveに失敗した場合はカウントして、特定の回数でしばらくチェックを禁止する。
				if( !($c = $this->Session->read('Twitter.count')) ){
					$c = 0;
				}
				$this->Session->write('Twitter.count', ++$c);
				//var_dump($c);
				
				//八回失敗したらしばらく認証チェック禁止
				if($c >= 8){
					$this->Session->write('Twitter.stopped', time()+(60*10) ); //認証禁止する期限を設定
					//$this->Session->del('Twitter.count');
					//var_dump($this->Session->read('Twitter.stopped'));
				}
			}
		}
		
		if (empty($this->data)) {
			$this->User->unbindModel(array('hasMany'=>array('Task')),false);  //taskの情報はいらないのでunbind
			$this->data = $this->User->read(null, $id);
		}
		
		
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for User', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->User->del($id)) {
			$this->Session->setFlash(__('User deleted', true));
			$this->redirect(array('action'=>'index'));
		}
	}
	
	function recent_password() {
		$this->view = "View";  //qdmailとdebug kitを併用するとエラー起きるのでその対策、本番環境では当然いらない
		
		$this->User->unbindModel(array('hasMany'=>array('Task')),false); //twitter投稿用のuser情報だけ欲しいのでunbind
		
		if ( !empty($this->data)  ) {
			
			//pr($this->data['User']['email_or_username']);	
			if(strpos($this->data['User']['email_or_username'],'@')){   // @が0文字目かfalseならユーザ名
				//1文字目以降に@があればemailと判断(乱暴だがいいんじゃないかな、後でもうちょっと厳密にするかも)
				//echo 'email!';
				$user = $this->User->findAllByEmail( $this->data['User']['email_or_username']); 
				
			}else{
				//なければユーザ名
				//echo 'username!';
				$user = $this->User->findAllByUsername( $this->data['User']['email_or_username'] ); 
			}
			
			
			//pr($user);
			if( isset($user[0]['User']['email']) && $user[0]['User']['email'] ){  //emailが保存されていれば
				
				//email or usernameから登録情報が見つかればリセット用IDをDB挿入後、メール送信
				$forget_token = md5(uniqid(mt_rand(), true));
				//pr($forget_token);
				
				//ドメインを含んだ絶対URLの取得
				App::import('Helper', 'Html');
				$html = new HtmlHelper();
				$base_url = $html->url(array('controller'=>'users','action'=>'reset_password','email'=>$user[0]['User']['email'],'token'=>$forget_token,),true);
				//pr($base_url);
				
				//保存する
				$this->data['User']['id'] = $user[0]['User']['id'];
				$this->data['User']['token'] = $forget_token;
				if($this->User->save($this->data)) {
					//メールを送信
					$this->QdmailWrap->address = $user[0]['User']['email'];
					$this->QdmailWrap->subject = 'パスワードをリセット';
					$this->QdmailWrap->message = "こんにちは。\n\nいつもListterを使ってくれてありがとうございます。\nパスワードを思い出せなくなったんですね。誰でもよくあることです。\n\nブラウザでこのリンクを開いてください：\n\n{$base_url}\n\nこのページにアクセスすることで、あなたのパスワードがリセットされます。\nログインした後に、新しいパスワードを設定してください。\n\nでは、\nりすったー";
					
					if( $this->QdmailWrap->post() ){
						//メール送信に成功したら
						$this->Session->setFlash(__('パスワードをリセットする手順を書いたメールを送りました。', true));
						$this->redirect('/home');
					}
				}
				
			
			}else{
				//email保存されていない or username(email)から登録情報が見つからなければ、そのまま戻る
				if($user){
					$this->Session->setFlash(__('残念ですが、あなたのアカウントでメールアドレスが設定されていません。', true));
				}else{
					$this->Session->setFlash(__('おやおや、あなたのアカウントが見つかりません。', true));
				}
				
				$this->data['User']['email_or_username'] = null;
			}
			
		}
	}
	
	function reset_password() {
		
		//pr($this->params);
		//urlのemailとtokenをチェック
		if( isset($this->params['email']) && isset($this->params['token']) ){
			//echo 'ok!!';
			
			$user = $this->User->find('all', array(
													'recursive' => 0, //int
													//'fields' => 'DISTINCT Article.user_id',
													'conditions' => array(
																			'email =' => $this->params['email'],
																			'token =' => $this->params['token'],
																		)
			));
			//pr($user);
			
			if(count($user) === 1){
				//echo '確認しますた!';
				
				//画面遷移用にset
				$params = array('email' => $this->params['email'],'token' => $this->params['token']);
				$this->set('params', $params );
				
				//ここから新しいパスワードの保存の開始
				if (!empty($this->data)) {
					//$id = $this->Auth->user('id');
					$this->data['User']['id'] = $user[0]['User']['id']; //保存時に自分のidを指定
					//modelでは(Authの)passwordメソッドが使えないからここで用意した値をmodelでバリデーション
					$this->data['User']['check_current_password'] = $user[0]['User']['password'];
					$this->data['User']['check_password_confirm'] = $this->Auth->password($this->data['User']['password_confirm']);
					$this->data['User']['password'] = $this->Auth->password($this->data['User']['password']);
					//$this->data['User']['password'] = $this->Auth->password($this->data['User']['password']);
					
					//pr($this->data['User']['password']);
					
					//tokenを消す(nullにする)
					$this->data['User']['token'] = null;
					
					if ($this->User->save($this->data)) {

						
						//$this->Session->setFlash(__('The User has been saved', true));
						$this->Session->setFlash(__('パスワードを変更しました。', true));
						//print 'save!!';
						$this->redirect(array('controller' => 'users','action' => 'login'));
					}else{
						$this->data['User']['password'] = '';
						$this->data['User']['password_confirm'] = '';
						
					}
				}
				
				
			}else{
				$this->Session->setFlash(__('ごめんなさい。このユーザーがパスワードのリセットを要求したことを確認できません', true));
				$this->redirect(array('controller' => 'users','action' => 'recent_password'));
			}
			
		}
		
	}
	
	function complete_show_flag() {
		// show_flag 
		// :0 殿堂入りは非表示
		// :1 殿堂入りとそれ以外も表示
		// :2 殿堂入りのみ表示
		
		//var_dump($this->params['task_id']);
		$id = $this->Auth->user('id');
		$flag = $this->params['show_flag'];
	
		if ( !$id || !ctype_digit($id) ) {
			$this->Session->setFlash(__('Invalid id for Task', true));
			$this->redirect('/home');
		}
		
		
		/*
		//$no = $id;
		$tasks = $this->Task->find('all' , 
					 array(
						'fields'=>'Task.task,Task.user_id,Task.completed',
						'conditions' => array('Task.id' => $id),
						//'recursive' => 1
						//'conditions' => array('Task.id' => 7)
					)
			);

		//取得したtaskのuser_idとauthでログインしているユーザidが違ったら不正アクセスなのでトップに飛ばす
		if($this->Auth->user('id') !== $tasks[0]['Task']['user_id']){
			//$this->Session->setFlash(__('!!!!!!!!', true));
			$this->redirect('/home');
		}
		*/
		
		$this->User->set("id",$id);
		if( $this->User->saveField('complete_show_flag', $flag ) ){
			$this->Session->setFlash(__('殿堂入りの表示を変更しました。', true));
			$this->redirect('/home');
		}
		
		

		
	}

}
?>