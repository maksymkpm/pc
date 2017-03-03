<?php
namespace api\v1_0\controller;

use RuntimeException;

class TestController extends TestBaseController {
	
	protected function actionGet() {
		$this->response->set(
			['response' => 'get method']
		);
	}

	protected function actionEdit() {
		$this->response->set(
			['response' => 'Edit method']
		);
	}

	protected function actionCreate() {
		$this->response->set(
			['response' => 'Create method']
		);
	}
	
	protected function actionDelete() {
		$this->response->set(
			['response' => 'Delete method']
		);
	}
}
