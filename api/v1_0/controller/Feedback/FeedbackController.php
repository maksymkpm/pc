<?php
namespace api\v1_0\controller\Feedback;

use \RequestParameters\FeedbackIssueCreate;
use \RequestParameters\FeedbackCommentCreate;
use RuntimeException;

class FeedbackController extends FeedbackBaseController {
	protected $noTokenAction = [
		
	];

	protected function actionIssueCreate() {
		$get = $this->request->dataPost();

		$this->response->set([]);
	}
	
	protected function actionCommentCreate() {
		$get = $this->request->dataPost();

		$this->response->set([]);
	}
}