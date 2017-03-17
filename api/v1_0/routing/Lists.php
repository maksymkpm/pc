<?php
use api\v1_0\controller\Lists\ListController;

use \rest\router;
use \rest\request;

router::addController('', ListController::class);

router::controller(ListController::class)
	->addAction(request::HTTP_GET, 'issue/list', 'issuelist');
