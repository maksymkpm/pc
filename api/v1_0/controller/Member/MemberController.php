<?php
namespace api\v1_0\controller\Member;

use \RequestParameters\MemberGet;
use \RequestParameters\MemberCreate;
use \RequestParameters\MemberEdit;
use RuntimeException;

class MemberController extends MemberBaseController {
	protected $member_id;
	protected $token;
	
	protected $noTokenAction = [
		'Auth', 'Create'
	];

	protected function actionAuth() {
        $request = $this->request->dataPost();
		$member = \Member::Auth($request);

		$memberData = [];
		if (!is_null($member)) {
			$memberData = $member->returnData();
		}

		$this->response->set($memberData);
	}

	//return member by provided token
	protected function actionGet() {
        $member = \Member::Get($this->member_id);
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