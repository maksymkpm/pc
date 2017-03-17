<?php
namespace api\v1_0\controller\Lists;

use RuntimeException;

class ListController extends ListBaseController {
	protected function actionIssueList() {
		$this->response->set([]);
	}
}