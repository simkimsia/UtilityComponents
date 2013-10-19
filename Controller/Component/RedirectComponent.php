<?php
/**
 * Adds extra logic to allow other kinds of redirect values depending on referer
 * currently meant for edit actions
 * 
 * Inspired by
 * public function edit($id = null) {
		if (!$this->Batch->exists($id)) {
			throw new NotFoundException(__('Invalid batch'));
		}
		$referer = $this->referer();
		$beforeEdit = (strpos($referer, "batches/edit") === false);
		if ($beforeEdit) {
			$referer = $this->Session->write('Batches.beforeEdit', $referer);
		} else {
			$referer = $this->Session->read('Batches.beforeEdit');
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Batch->save($this->request->data)) {
				$this->Session->setFlash(__('The batch has been saved'));
				if (empty($referer)) {
					$this->redirect(array('action' => 'index'));
				} else {
					$this->redirect($referer);
				}
			} else {
				$this->Session->setFlash(__('The batch could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Batch.' . $this->Batch->primaryKey => $id));
			$batch = $this->Batch->find('first', $options);
			$this->request->data = $batch;
			$this->set('batch', $batch);
		}
		$years = $this->Batch->Year->find('list');
		$customers = $this->Batch->getAssoc('Customer', 'list');
		$this->set(compact('years', 'customers'));
	}
 *
 * Cake2.x compatible Component
 *
 * Copyright 2013, Kim Stacks
 * Singapore
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2013, Kim Stacks.
 * @link http://stacktogether.com
 * @author Kim Stacks <kim@stacktogether.com>
 * @package UtilityComponents
 * @subpackage UtilityComponents.Controller.Component
 * @filesource
 * @version 0.1
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @lastmodified 2013-10-19
 */
App::uses('Component', 'Controller');
class RedirectComponent extends Component {

/**
 * Behavior settings
 *
 * @access public
 * @var array
 */
	public $settings = array();

/**
 * Cake Controller
 *
 * @access public
 * @var array
 */
	public $controller = null;

/**
 * Initialize function
 *
 *
 * @param controller object $controller
 * @param array $settings
 */
	public function initialize(Controller $controller) {
		$this->controller = $controller;
	}

/**
 *
 * @param controller object $controller 
 */
	public function startup(Controller $controller) {
		$this->controller = $controller;
	}

/**
 *
 * retrieve redirect values for edit actions. default is the referer before the edit
 * works even after multiple failed submissions. Relies on Session Component
 *
 * @param $options Optional. Currently not used.
 * @return boolean
 */
	public function getRefererBeforeEdit($options = array()) {
		$controllerName = $this->controller->name;
		$referer = $this->controller->referer();
		$beforeEdit = (strpos($referer, "$name/edit") === false);
		if ($beforeEdit) {
			$referer = $this->controller->Session->write("RedirectComponent.$name.beforeEdit", $referer);
		} else {
			$referer = $this->controller->Session->read("RedirectComponent.$name.beforeEdit");
		}
		return $referer;
	}
}