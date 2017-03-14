<?php
namespace api\v1_0\controller\test;

use RuntimeException;

class TestController extends TestBaseController {
	
	protected function actionGet() {
		$this->response->set(
			['method' => 'get method']
		);
	}

	protected function actionEdit() {
		$this->response->set(
			['method' => 'Edit method',
				'username' => $this->request->dataPost('username'),
				'password' => $this->request->dataPost('password')]
		);
	}

	protected function actionCreate() {		
		$this->response->set(
			[
				'method' => 'Create method',
				'username' => $this->request->dataPost('username'),
				'password' => $this->request->dataPost('password')
			]
		);
	}
	
	protected function actionDelete() {
		$this->response->set(
			['method' => 'Delete method']
		);
	}
	
	protected function actionOptions() {
		$this->response->set(
			['method' => 'Options method']
		);
	}
}
