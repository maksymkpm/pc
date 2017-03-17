<?php
namespace api\v1_0\controller\Feedback;

use rest\components\auth\BearerAuth;
use api\v1_0\controller\BaseController as BaseController;

abstract class FeedbackBaseController extends BaseController {
	protected function runBeforeAction() {
		parent::runBeforeAction();

		$token = (new BearerAuth($this->request))->getToken();

		if (!in_array($this->action, $this->noTokenAction)) {
			if (empty($token)) {
				throw new \AuthenticationException('Invalid access token', 403);
			}
		}
	}
}
