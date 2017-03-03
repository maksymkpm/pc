<?php
namespace api\v1_0\controller;

use rest\components\auth\BearerAuth;
/**
 * Base class for all controllers in this version of the API server.
 * It can contain some global logic, what is actual for all controllers.
 * 		- global prepare logic before executing action
 * 		- global post processing logic after executing action
 * 		- global helper functions
 *
 * NOTE: do not update the common class \rest\controller, use this class inside every versions of the server
 */
abstract class TestBaseController extends BaseController  {
	/**
	 * Validate user token and permissions
	 */
	protected function runBeforeAction() {
		parent::runBeforeAction();

		$token = (new BearerAuth($this->request))->getToken() ?? $this->request->dataPost('token');

		if (empty($token)) {
			throw new \AuthenticationException("Invalid access token", 403);
		}
	}
}
