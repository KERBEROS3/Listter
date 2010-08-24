<?php
App::import('Vendor', 'include_path');
App::import('Vendor', 'twitteroauth/twitteroauth');

class OauthTwitterComponent extends Object{
	//require_once('twitteroauth/twitterOAuth.php');
	//var $components = array('TwitterOAuth');
	
	
	/* Consumer key from twitter */
	var $consumer_key = 'v6iJtbvteWh7y6WLjZs1Q';
	/* Consumer Secret from twitter */
	var $consumer_secret = 'OyF6SspfratMsuuHSVyWMoJUNl0F29qMTYtKsEjag';
	/* Set up placeholder */
	//var $content = NULL;
	/* Set state if previous session */
	//var $state = $_SESSION['oauth_state'];
	
	
	function getAuthorizeURL(){
		/* Create TwitterOAuth object with app key/secret */
		$to = new TwitterOAuth($this->consumer_key, $this->consumer_secret);
		/* Request tokens from twitter */
		$tok = $to->getRequestToken();
		
		/* Save tokens for later */
		//$_SESSION['oauth_request_token'] = $token = $tok['oauth_token'];
		//$_SESSION['oauth_request_token_secret'] = $tok['oauth_token_secret'];
		$token = $tok['oauth_token'];
		
		/* Build the authorization URL */
		$request_link = $to->getAuthorizeURL($token);
		
		return array('oauth_token' => $tok['oauth_token'],'oauth_token_secret' => $tok['oauth_token_secret'],'request_link'=>$request_link);
	
	}
	
	function getAccessToken($request_token,$request_token_secret){
		//pr($request_token);
		//pr($request_token_secret);
		/* Create TwitterOAuth object with app key/secret and token key/secret from default phase */
		$to = new TwitterOAuth($this->consumer_key, $this->consumer_secret, $request_token, $request_token_secret);
		//echo 'check';
		/* Request access tokens from twitter */
		$tok = @$to->getAccessToken(); //request_tokenとかが古くなってるとNotice吐くので抑制(twitteroauth.php)
		
		//echo '<hr />';
		//pr($tok);
		//echo '<hr />';
		
		//きちんと情報が受けれてなければ return false;
		if(!isset($tok['user_id'])){
			//echo '$tok is false, die!';
			return false;	
		}
		
		return $tok;
		
		/*
		uses('Xml');
		$xml = new XML($tok);
		$xml_array = Set::reverse($xml);
		
		
		if(!isset($xml[children][0][attributes][user_id])){
			echo 'xml is false, die!';
			return false;	
		}
		*/
		
		//pr(isset($xml[children][0][attributes][user_id]));
		

		
		/* Create TwitterOAuth with app key/secret and user access key/secret */
		//$to = new TwitterOAuth($this->consumer_key, $this->consumer_secret, $tok['oauth_token'], $tok['oauth_token_secret']);
		/* Run request on twitter API as user. */
		//$content = $to->OAuthRequest('https://twitter.com/account/verify_credentials.xml', array(), 'GET');
		//pr($content)
		
		//return $content;
	}
	
	function check($oauth_token,$oauth_token_secret){
		//pr($oauth_token);
		//pr($oauth_token_secret);
		
		/* Create TwitterOAuth with app key/secret and user access key/secret */
		$to = new TwitterOAuth($this->consumer_key, $this->consumer_secret, $oauth_token, $oauth_token_secret);
		/* Run request on twitter API as user. */
		$content = $to->OAuthRequest('https://twitter.com/account/verify_credentials.xml', array(), 'GET');
		//$content = $to->OAuthRequest('https://twitter.com/statuses/update.xml', array('status' => 'Test OAuth update. #testoauth'), 'POST');
		//pr($content);
		
		uses('Xml');
		$xml = new XML($content);
		$xml_array = Set::reverse($xml);
		
		//pr($xml_array);
		
		if(isset($xml_array['Hash']['error'])){
			return false;
		}else{
			return true;
		}
		
		//return $content;
		
	}
	
	function post($status,$oauth_token,$oauth_token_secret){
		//pr($status);
		//pr($oauth_token);
		//pr($oauth_token_secret);
		
		/* Create TwitterOAuth with app key/secret and user access key/secret */
		$to = new TwitterOAuth($this->consumer_key, $this->consumer_secret, $oauth_token, $oauth_token_secret);
		/* Run request on twitter API as user. */
		//$content = $to->OAuthRequest('https://twitter.com/account/verify_credentials.xml', array(), 'GET');
		$content = $to->OAuthRequest('https://twitter.com/statuses/update.xml', array('status' => $status), 'POST');
		//pr($content);
		
		uses('Xml');
		$xml = new XML($content);
		$xml_array = Set::reverse($xml);
		
		//pr($xml_array);
		
		if(isset($xml_array['Hash']['error'])){
			return false;
		}else{
			return true;
		}
		
		//return $content;
		
	}
	
	/*
	var $components = array('Qdmail');
	
	var $address = false;
	var $subject = false;
	var $message = false;
	
	function post(){
		if( !$this->address || !$this->message || !$this->subject){
			return false;
		}
		
		
		$param = array(
			'host'=>'ziro.jp',
			'port'=>'5190',
			'from'=>'ryota@heeha.ws',
			'user'=>'ryoziro',   //postmaster@example.com
			'pass' => 'y20now2411',
			'protocol'=>'SMTP_AUTH',
			);
		
		$this->Qdmail->to("$this->address");
		$this->Qdmail->subject("$this->subject");
		$this->Qdmail->from('kerberos@ziro.jp','Listter');

		$this->Qdmail->smtp(true);
		$this->Qdmail->smtpServer($param);
		
		$this->Qdmail->cakeText("$this->message"); //Cakephpのemailのlayoutを使用している。
		//$this->Qdmail -> text( '本文をここにかきます' );

		$fg=$this->Qdmail->send();
		//var_dump($fg);
		return $fg;
	}
	*/
}

?>