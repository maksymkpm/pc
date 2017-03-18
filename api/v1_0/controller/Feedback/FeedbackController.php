<?php
namespace api\v1_0\controller\Feedback;

use \RequestParameters\FeedbackIssueCreate;
use \RequestParameters\FeedbackCommentCreate;
use RuntimeException;

class FeedbackController extends FeedbackBaseController {
	protected $noTokenAction = [
		
	];

	protected function actionIssueCreate() {
        $post = $this->request->dataPost();
        $params = new FeedbackIssueCreate($post);
        $Feedback = \Feedback::IssueCreate($params);

        $this->response->set(['result' => $Feedback]);
	}
	
	protected function actionCommentCreate() {
        $post = $this->request->dataPost();
        $params = new FeedbackCommentCreate($post);
        $Feedback = \Feedback::CommentCreate($params);

        $this->response->set(['result' => $Feedback]);
	}
}