<?php
namespace api\v1_0\controller\Issue;

use rest\components\auth\BearerAuth;
use api\v1_0\controller\BaseController as BaseController;

abstract class IssueBaseController extends BaseController  {
	protected function runBeforeAction() {
		parent::runBeforeAction();
	}
}
