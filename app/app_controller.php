<?php

class AppController extends Controller {
	/**
	* Run whenever auto login is successful
	* @param array $user - The Auth user session
	* @access private
	*/
	
	/*
	function _autoLogin($user) {
		$this->redirect(array('controller'=>'users','action'=>'login'));
	}
	*/
	
	//debug kit	どうもトップの表示がおかしくなるがデバッグ用なので問題無しと思われる
	var $components = array('DebugKit.Toolbar');
	
	// .json形式を標準で使えるようにする
	
	
	//var $components = array('RequestHandler');
	/*
	function beforeFilter() {
		//$this->RequestHandler->setContent('json');
	}
	*/
	
	/*
	function beforeFilter() {
		//cookieコンポーネントの共通設定 //でも共通より個別のほうがセキュアなのでコメントアウト
		$this->Cookie->name = 'listter_setting';
		$this->Cookie->key = '~SI2@2()qVs*&sXOw!a28.<';
		
	}
	*/



}

?>
