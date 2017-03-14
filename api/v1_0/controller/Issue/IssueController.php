<?php
namespace api\v1_0\controller\Issue;

use RuntimeException;

class IssueController extends IssueBaseController {
	
	protected function actionGet() {
		$this->response->set(
			['method' => 'get issue']
		);
	}

	protected function actionEdit() {
		$this->response->set(
			['method' => 'edit issue']
		);
	}

	protected function actionCreate() {		
		$this->response->set(
			['method' => 'create issue']
		);
	}
	
	protected function actionDelete() {
		$this->response->set(
			['method' => 'delete issue']
		);
	}

	protected function actionReturnIssueClassifications() {
		$this->response->set(\Issue::ReturnIssueClassifications());
	}
}
