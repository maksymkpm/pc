<?php
namespace api\v1_0\controller\Member;

use \RequestParameters\FeedbackIssueCreate;
use \RequestParameters\FeedbackCommentCreate;
use RuntimeException;

class MemberFeedbackController extends MemberBaseController {
	protected $member_id;
	protected $token;
	protected $noTokenAction = [];

	protected function actionIssueCreate() {
        $request = $this->request->dataPost();
		$request['member_id'] = $this->member_id;

        $params = new FeedbackIssueCreate($request);
        $Feedback = \Feedback::IssueCreate($params);

        $this->response->set(['result' => $Feedback]);
	}

	protected function actionCommentCreate() {
        $request = $this->request->dataPost();
		$request['member_id'] = $this->member_id;

        $params = new FeedbackCommentCreate($request);
        $Feedback = \Feedback::CommentCreate($params);

        $this->response->set(['result' => $Feedback]);
	}
}