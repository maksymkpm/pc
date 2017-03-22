<?php
use api\v1_0\controller\Member\MemberController;

use \rest\router;
use \rest\request;

router::addController('', MemberController::class);

router::controller(MemberController::class)
	->addAction(request::HTTP_GET, 'member', 'get');

router::controller(MemberController::class)
	->addAction(request::HTTP_POST, 'member', 'create');

router::controller(MemberController::class)
	->addAction(request::HTTP_POST, 'member/auth', 'auth');