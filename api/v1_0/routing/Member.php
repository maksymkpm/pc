<?php
use api\v1_0\controller\Member\MemberController;
use api\v1_0\controller\Member\MemberIssueController;
use api\v1_0\controller\Member\MemberFollowController;

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

router::addController('', MemberFollowController::class);
router::controller(MemberFollowController::class)
	->addAction(request::HTTP_POST, 'member/follow/issue', 'Issue');

router::controller(MemberFollowController::class)
	->addAction(request::HTTP_POST, 'member/follow/member', 'Member');

router::controller(MemberFollowController::class)
	->addAction(request::HTTP_POST, 'member/stopfollow/issue', 'StopFollowIssue');

router::controller(MemberFollowController::class)
	->addAction(request::HTTP_POST, 'member/stopfollow/member', 'StopFollowMember');
