<?php
namespace api\v1_0\controller\Lists;

use \RequestParameters\ListGet;
use RuntimeException;

class ListController extends ListBaseController {
	protected $noTokenAction = [
		'IssueList',
	];

	protected function actionIssueList() {
		$get = $this->request->dataGet();
//var_dump($get);
		$params = new ListGet($get);
//var_dump($params);
		$issues = \Lists::Issues($params);

		$this->response->set($issues);
	}
}