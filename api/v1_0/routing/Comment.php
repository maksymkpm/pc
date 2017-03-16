<?php
use api\v1_0\controller\Comment\CommentController;

use \rest\router;
use \rest\request;

router::addController('', CommentController::class);

router::controller(CommentController::class)
	->addAction(request::HTTP_GET, 'comment', 'get');

router::controller(CommentController::class)
	->addAction(request::HTTP_POST, 'comment', 'create');

router::controller(CommentController::class)
	->addAction(request::HTTP_PUT, 'comment', 'edit');

router::controller(CommentController::class)
	->addAction(request::HTTP_DELETE, 'comment', 'delete');
