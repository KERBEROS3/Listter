<?php
class QdmailWrapComponent extends Object{
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
}

?>