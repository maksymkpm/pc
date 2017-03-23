<?php
use api\v1_0\controller\Member\MemberFeedbackController;

use \rest\router;
use \rest\request;

router::addController('', MemberFeedbackController::class);

router::controller(MemberFeedbackController::class)
	->addAction(request::HTTP_POST, 'feedback/issue', 'IssueCreate');

router::controller(MemberFeedbackController::class)
	->addAction(request::HTTP_POST, 'feedback/comment', 'CommentCreate');
