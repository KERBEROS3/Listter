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
	
	//debug kit	�ǂ����g�b�v�̕\�������������Ȃ邪�f�o�b�O�p�Ȃ̂Ŗ�薳���Ǝv����
	var $components = array('DebugKit.Toolbar');
	
	// .json�`����W���Ŏg����悤�ɂ���
	
	
	//var $components = array('RequestHandler');
	/*
	function beforeFilter() {
		//$this->RequestHandler->setContent('json');
	}
	*/
	
	/*
	function beforeFilter() {
		//cookie�R���|�[�l���g�̋��ʐݒ� //�ł����ʂ��ʂ̂ق����Z�L���A�Ȃ̂ŃR�����g�A�E�g
		$this->Cookie->name = 'listter_setting';
		$this->Cookie->key = '~SI2@2()qVs*&sXOw!a28.<';
		
	}
	*/



}

?>
