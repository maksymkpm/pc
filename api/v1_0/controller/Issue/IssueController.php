<?php
namespace api\v1_0\controller\Issue;

use \RequestParameters\IssueGet;
use \RequestParameters\IssueCreate;

use RuntimeException;

class IssueController extends IssueBaseController {
	protected function actionGet() {
		$get = $this->request->dataGet();
		$params = new IssueGet($get);
		$issue = \Issue::Get((int) $params->issue_id);
		
		$result = [
			'issue_id' => $issue->getIssueId(),
			'member_id' => $issue->getMemberId(),
			'title' => $issue->getTitle(),
			'description' => $issue->getDescription(),
			'class_id' => $issue->getClassId(),
			'category_id' => $issue->getCategoryId(),
			'object_id' => $issue->getObjectId(),
			'subject_id' => $issue->getSubjectId(),
			'priority' => $issue->getPriorityId(),
			'status' => $issue->getStatus(),
			'comments_amount' => $issue->getCommentsAmount(),
			'date_added' => $issue->getDateAdded(),
		];
		
		$this->response->set($result);
	}

	protected function actionCreate() {
		$post = $this->request->dataPost();
		$params = new IssueCreate($post);
		$issue = \Issue::Create($params);

		if (!$issue) {
			throw new RuntimeException('Issue not created');
		}

		$result = [
			'issue_id' => $issue->getIssueId(),
			'member_id' => $issue->getMemberId(),
			'title' => $issue->getTitle(),
			'description' => $issue->getDescription(),
			'class_id' => $issue->getClassId(),
			'category_id' => $issue->getCategoryId(),
			'object_id' => $issue->getObjectId(),
			'subject_id' => $issue->getSubjectId(),
			'priority' => $issue->getPriorityId(),
			'status' => $issue->getStatus(),
			'comments_amount' => $issue->getCommentsAmount(),
			'date_added' => $issue->getDateAdded(),
		];

		$this->response->set($result);
	}

	protected function actionEdit() {
		$this->response->set(
			['method' => 'edit issue']
		);
	}

	protected function actionDelete() {
		$this->response->set(
			['method' => 'delete issue']
		);
	}

	protected function actionReturnIssueClassifications() {
		$this->response->set(\Issue::ReturnIssueClassifications());
	}
}
