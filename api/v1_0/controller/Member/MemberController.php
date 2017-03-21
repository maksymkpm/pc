<?php
namespace api\v1_0\controller\Member;

use \RequestParameters\MemberGet;
use \RequestParameters\MemberCreate;
use \RequestParameters\MemberEdit;
use RuntimeException;

class MemberController extends MemberBaseController {
	protected $noTokenAction = [
		'create'
	];

	protected function actionGet() {
        $request = $this->request->dataGet();
		$params = new MemberGet($request);

        $member = \Member::Get($params->member_id);

		$memberData = $member->returnData();
		$this->response->set($memberData);
	}

	protected function actionCreate() {
        $request = $this->request->dataPost();
        $params = new MemberCreate($request);
		$member = \Member::Create($params);
		$memberData = $member->returnData();
		$this->response->set($memberData);
	}

}