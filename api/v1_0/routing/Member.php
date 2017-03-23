<?php
use api\v1_0\controller\Member\MemberController;
use api\v1_0\controller\Member\MemberIssueController;
use \rest\router;
use \rest\request;

router::addController('', MemberController::class);

router::controller(MemberController::class)
	->addAction(request::HTTP_GET, 'member', 'get');

router::controller(MemberController::class)
	->addAction(request::HTTP_POST, 'member', 'create');

router::controller(MemberController::class)
	->addAction(request::HTTP_POST, 'member/auth', 'auth');

	
router::addController('', MemberIssueController::class);
router::controller(MemberIssueController::class)
	->addAction(request::HTTP_GET, 'member/issue/list', 'IssueList');
