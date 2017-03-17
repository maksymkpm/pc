<?php
use api\v1_0\controller\Feedback\FeedbackController;

use \rest\router;
use \rest\request;

router::addController('', FeedbackController::class);

router::controller(FeedbackController::class)
	->addAction(request::HTTP_POST, 'feedback/issue', 'IssueCreate');

router::controller(FeedbackController::class)
	->addAction(request::HTTP_POST, 'feedback/comment', 'CommentCreate');
