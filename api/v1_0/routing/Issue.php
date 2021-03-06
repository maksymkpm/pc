<?php
use api\v1_0\controller\Issue\IssueController;

use \rest\router;
use \rest\request;

router::addController('', IssueController::class);

router::controller(IssueController::class)
	->addAction(request::HTTP_POST, 'issue', 'create');

router::controller(IssueController::class)
	->addAction(request::HTTP_GET, 'issue', 'get');

router::controller(IssueController::class)
	->addAction(request::HTTP_PUT, 'issue', 'edit');

router::controller(IssueController::class)
	->addAction(request::HTTP_PUT, 'issue/open', 'open');

router::controller(IssueController::class)
	->addAction(request::HTTP_PUT, 'issue/close', 'close');

router::controller(IssueController::class)
	->addAction(request::HTTP_PUT, 'issue/delete', 'delete');

router::controller(IssueController::class)
	->addAction(request::HTTP_PUT, 'issue/archive', 'archive');
