<?php
namespace api\v1_0\controller\test;

use rest\components\auth\BearerAuth;
use api\v1_0\controller\BaseController as BaseController;

abstract class TestBaseController extends BaseController  {
	protected function runBeforeAction() {
		parent::runBeforeAction();

		$token = (new BearerAuth($this->request))->getToken() ?? $this->request->dataPost('token');

		if (empty($token)) {
			throw new \AuthenticationException("Invalid access token", 403);
		}
	}
}
