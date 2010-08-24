<?php
class PiecesController extends AppController {

	var $name = 'Pieces';
	var $helpers = array('Html', 'Form');
	//var $components = array('');

	function index() {
		$this->Piece->recursive = 0;
		$this->set('pieces', $this->paginate());
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid Piece.', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->set('piece', $this->Piece->read(null, $id));
	}

	function add() {
		if (!empty($this->data)) {
			$this->Piece->create();
			if ($this->Piece->save($this->data)) {
				$this->Session->setFlash(__('The Piece has been saved', true));
				$this->redirect(array('action'=>'index'));
			} else {
				$this->Session->setFlash(__('The Piece could not be saved. Please, try again.', true));
			}
		}
		$tasks = $this->Piece->Task->find('list', array('fields' => array('Task.task')));
		var_dump($tasks);
		$this->set(compact('tasks'));
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid Piece', true));
			$this->redirect(array('action'=>'index'));
		}
		if (!empty($this->data)) {
			if ($this->Piece->save($this->data)) {
				$this->Session->setFlash(__('The Piece has been saved', true));
				$this->redirect(array('action'=>'index'));
			} else {
				$this->Session->setFlash(__('The Piece could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Piece->read(null, $id);
		}
		$tasks = $this->Piece->Task->find('list');
		$this->set(compact('tasks'));
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for Piece', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Piece->del($id)) {
			$this->Session->setFlash(__('Piece deleted', true));
			$this->redirect(array('action'=>'index'));
		}
	}

}
?>