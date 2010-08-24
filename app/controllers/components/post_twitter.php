<?php
class PostTwitterComponent extends Object{
	var $username = false;
	var $password = false;
	
	function post($message = false){
			//var_dump($this->username);
			//var_dump($this->password);
			//var_dump($message);
			
			//username または password または message が一つでもfalseなら
			if( !$this->username || !$this->password || !$message){
				return false;
			}
		
			$url = "http://twitter.com/statuses/update.xml?";
			//$username = 'kerberos3';
			//$password = 'ryota361';
			$params = "status=". rawurlencode("$message");
			$source= '&source=listter.jp'; //クライアント名 http://twitter.com/oauth_clients/new で申請

			$result = @file_get_contents($url.$params.$source , false, stream_context_create(array(
				"http" => array(
					"method" => "POST",
					"header" => "Authorization: Basic ". base64_encode($this->username. ":". $this->password)
				)
			)));

			//var_dump($result);

			if($result){
				return true;
				//print '投稿成功';
			}else{
				return false;
				//print '投稿失敗';
			}
	}
}

?>