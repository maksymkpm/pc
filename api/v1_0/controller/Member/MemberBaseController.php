<?php
namespace api\v1_0\controller\Member;

use rest\components\auth\BearerAuth;
use api\v1_0\controller\BaseController as BaseController;

abstract class MemberBaseController extends BaseController {
	protected $member_id;
	protected $token;

	protected function runBeforeAction() {
		parent::runBeforeAction();

		$token = (new BearerAuth($this->request))->getToken();
		if (!in_array($this->action, $this->noTokenAction)) {
			if (empty($token)) {
				throw new \AuthenticationException('Invalid access token', 403);
			}

			//verify token
			$member_id = \Member::getMemberByToken($token);
			if (!empty($member_id)) {
				$this->member_id = $member_id;
				$this->token = $token;
			}
		}
	}
	
	protected function runAfterAction() {
		parent::runAfterAction();

		//update token expiry
		if (!in_array($this->action, $this->noTokenAction)) {			
			\Member::tokenExpiryUpdate($this->member_id, $this->token);
		}

	}
}
