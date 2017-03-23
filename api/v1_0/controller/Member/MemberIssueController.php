<?php
namespace api\v1_0\controller\Member;

use \RequestParameters\ListMemberIssues;
use RuntimeException;

class MemberIssueController extends MemberBaseController {
	protected $member_id;
	protected $token;

	protected $noTokenAction = [];

	protected function actionIssueList() {
        $request = $this->request->dataGet();
		$request['member_id'] = $this->member_id;

		$params = new ListMemberIssues($request);
		$member = \Lists::MemberIssues($params);

		$this->response->set($member);
	}
}

