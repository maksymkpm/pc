<?php
use api\v1_0\controller\Classification\ClassificationController;

use \rest\router;
use \rest\request;

router::addController('', ClassificationController::class);

router::controller(ClassificationController::class)
	->addAction(request::HTTP_GET, 'classification', 'classification');

router::controller(ClassificationController::class)
	->addAction(request::HTTP_GET, 'classification/classes', 'classes');

router::controller(ClassificationController::class)
	->addAction(request::HTTP_GET, 'classification/objects', 'objects');

router::controller(ClassificationController::class)
	->addAction(request::HTTP_GET, 'classification/subjects', 'subjects');

router::controller(ClassificationController::class)
	->addAction(request::HTTP_GET, 'classification/categories', 'categories');
