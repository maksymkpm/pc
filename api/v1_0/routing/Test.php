<?php
use api\v1_0\controller\TestController;

use \rest\router;
use \rest\request;

router::addController('', TestController::class);

router::controller(TestController::class)
	->addAction(request::HTTP_POST, 'test', 'create');

router::controller(TestController::class)
	->addAction(request::HTTP_GET, 'test', 'get');

router::controller(TestController::class)
	->addAction(request::HTTP_PUT, 'test', 'edit');

router::controller(TestController::class)
	->addAction(request::HTTP_DELETE, 'test', 'delete');
