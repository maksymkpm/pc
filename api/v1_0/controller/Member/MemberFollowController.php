<?php
namespace api\v1_0\controller\Member;

use \RequestParameters\FollowMember;
use \RequestParameters\FollowIssue;
use RuntimeException;

class MemberFollowController extends MemberBaseController {
	protected $member_id;
	protected $token;

	protected $noTokenAction = [];

	protected function actionIssue() {
        $request = $this->request->dataPost();
		$request['member_id'] = $this->member_id;
		$params = new FollowIssue($request);
		$result = \Follow::Issue($params);

		$this->response->set($result);
	}
	
	protected function actionMember() {
        $request = $this->request->dataPost();
		$request['follower_id'] = $this->member_id;
		$params = new FollowMember($request);
		$result = \Follow::Member($params);

		$this->response->set($result);
	}
	
	protected function actionStopFollowIssue() {
        $request = $this->request->dataPost();
		$request['member_id'] = $this->member_id;
		$params = new FollowIssue($request);
		$result = \Follow::stopFollowIssue($params);

		$this->response->set($result);
	}
	
	protected function actionStopFollowMember() {
         $request = $this->request->dataPost();
		$request['follower_id'] = $this->member_id;
		$params = new FollowMember($request);
		$result = \Follow::stopFollowMember($params);

		$this->response->set($result);
	}
}
