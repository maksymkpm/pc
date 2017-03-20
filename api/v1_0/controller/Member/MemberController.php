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
        $this->response->set([$request]);
	}
	
}