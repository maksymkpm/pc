<?php
namespace api\v1_0\controller\Classification;

use RuntimeException;

class ClassificationController extends ClassificationBaseController {
	protected function actionClassification() {
		$this->response->set(\Classification::ReturnClassification());
	}

	protected function actionClasses() {
		$this->response->set(\Classification::ReturnClasses());
	}

	protected function actionObjects() {
		$this->response->set(\Classification::ReturnObjects());
	}

	protected function actionSubjects() {
		$this->response->set(\Classification::ReturnSubjects());
	}

	protected function actionCategories() {
		$this->response->set(\Classification::ReturnCategories());
	}
}
