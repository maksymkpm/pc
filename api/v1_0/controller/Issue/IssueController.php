<?php
namespace api\v1_0\controller\Issue;

use \RequestParameters\IssueGet;
use \RequestParameters\IssueCreate;
use \RequestParameters\IssueEdit;

use RuntimeException;

class IssueController extends IssueBaseController {
	protected $noTokenAction = [
		'Get',
	];

	protected function actionGet() {
		$get = $this->request->dataGet();
		$params = new IssueGet($get);

		$issue = \Issue::Get((int) $params->issue_id);

		$result = [
			'issue_id' => $issue->data['issue_id'],
			'member_id' => $issue->data['member_id'],
			'title' => $issue->data['title'],
			'description' => $issue->data['description'],
			'class_id' => $issue->data['class_id'],
			'category_id' => $issue->data['category_id'],
			'object_id' => $issue->data['object_id'],
			'subject_id' => $issue->data['subject_id'],
			'priority' => $issue->data['priority'],
			'status' => $issue->data['status'],
			'helpful' => $issue->data['helpful'],
			'not_helpful' => $issue->data['not_helpful'],
			'comments_amount' => $issue->data['comments_amount'],
			'last_updated' => $issue->data['last_updated'],
			'date_added' => $issue->data['date_added'],
		];

		$this->response->set($result);
	}

	protected function actionCreate() {
		$post = $this->request->dataPost();
		$params = new IssueCreate($post);
		$issue = \Issue::Create($params);

		$result = [
			'issue_id' => $issue->data['issue_id'],
			'member_id' => $issue->data['member_id'],
			'title' => $issue->data['title'],
			'description' => $issue->data['description'],
			'class_id' => $issue->data['class_id'],
			'category_id' => $issue->data['category_id'],
			'object_id' => $issue->data['object_id'],
			'subject_id' => $issue->data['subject_id'],
			'priority' => $issue->data['priority'],
			'status' => $issue->data['status'],
			'helpful' => $issue->data['helpful'],
			'not_helpful' => $issue->data['not_helpful'],
			'comments_amount' => $issue->data['comments_amount'],
			'last_updated' => $issue->data['last_updated'],
			'date_added' => $issue->data['date_added'],
		];

		$this->response->set($result);
	}

	protected function actionEdit() {
		$put = $this->request->dataPost();
		$params = new IssueEdit($put);
		$issue = \Issue::Edit($params);

		$result = [
			'issue_id' => $issue->data['issue_id'],
			'member_id' => $issue->data['member_id'],
			'title' => $issue->data['title'],
			'description' => $issue->data['description'],
			'class_id' => $issue->data['class_id'],
			'category_id' => $issue->data['category_id'],
			'object_id' => $issue->data['object_id'],
			'subject_id' => $issue->data['subject_id'],
			'priority' => $issue->data['priority'],
			'status' => $issue->data['status'],
			'helpful' => $issue->data['helpful'],
			'not_helpful' => $issue->data['not_helpful'],
			'comments_amount' => $issue->data['comments_amount'],
			'last_updated' => $issue->data['last_updated'],
			'date_added' => $issue->data['date_added'],
		];

		$this->response->set($result);
	}
	
	protected function actionOpen() {
		$request = $this->request->dataPost();
		$params = new IssueEdit($request);
		
		$issue = \Issue::Open($params);

		$result = [
			'issue_id' => $issue->data['issue_id'],
		];

		$this->response->set($result);
	}
	
	protected function actionClose() {
		$request = $this->request->dataPost();
		$params = new IssueEdit($request);
		
		$issue = \Issue::Close($params);

		$result = [
			'issue_id' => $issue->data['issue_id'],
		];

		$this->response->set($result);
	}
	
	protected function actionDelete() {
		$request = $this->request->dataPost();
		$params = new IssueEdit($request);
		
		$issue = \Issue::Delete($params);

		$result = [
			'issue_id' => $issue->data['issue_id'],
		];

		$this->response->set($result);
	}
	
	protected function actionArchive() {
		$request = $this->request->dataPost();
		$params = new IssueEdit($request);
		
		$issue = \Issue::Archive($params);

		$result = [
			'issue_id' => $issue->data['issue_id'],
		];

		$this->response->set($result);
	}
}
