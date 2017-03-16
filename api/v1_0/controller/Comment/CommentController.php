<?php
namespace api\v1_0\controller\Comment;

use \RequestParameters\CommentGet;
use \RequestParameters\CommentCreate;
use \RequestParameters\CommentEdit;

use RuntimeException;

class CommentController extends CommentBaseController {
	protected $noTokenAction = [
		'Get',
	];

	protected function actionGet() {
		$get = $this->request->dataGet();
		$params = new CommentGet($get);

		$comment = \Comment::Get((int) $params->comment_id);

		$result = [
			'comment_id' => $comment->getCommentId(),
			'issue_id' => $comment->getIssueId(),
			'member_id' => $comment->getMemberId(),
			'message' => $comment->getMessage(),
			'status' => $comment->getStatus(),
			'last_updated' => $comment->getLastUpdated(),
			'date_added' => $comment->getDateAdded(),
		];

		$this->response->set($result);
	}

	protected function actionCreate() {
		$post = $this->request->dataPost();
		$params = new CommentCreate($post);
		$comment = \Comment::Create($params);

		$result = [
			'comment_id' => $comment->getCommentId(),
			'issue_id' => $comment->getIssueId(),
			'member_id' => $comment->getMemberId(),
			'message' => $comment->getMessage(),
			'status' => $comment->getStatus(),
			'last_updated' => $comment->getLastUpdated(),
			'date_added' => $comment->getDateAdded(),
		];

		$this->response->set($result);
	}

	protected function actionEdit() {
		$put = $this->request->dataPost();
		$params = new CommentEdit($put);
		$comment = \Comment::Edit($params);

		$result = [
			'comment_id' => $comment->getCommentId(),
			'issue_id' => $comment->getIssueId(),
			'member_id' => $comment->getMemberId(),
			'message' => $comment->getMessage(),
			'status' => $comment->getStatus(),
			'last_updated' => $comment->getLastUpdated(),
			'date_added' => $comment->getDateAdded(),
		];

		$this->response->set($result);
	}

	protected function actionPublish() {
		$delete = $this->request->dataPost();
		$params = new CommentEdit($delete);
		$comment = \Comment::Publish($params);

		$result = [
			'comment_id' => $comment->getCommentId(),
		];

		$this->response->set($result);
	}

	protected function actionDelete() {
		$delete = $this->request->dataPost();
		$params = new CommentEdit($delete);
		$comment = \Comment::Delete($params);

		$result = [
			'comment_id' => $comment->getCommentId(),
		];

		$this->response->set($result);
	}

	protected function actionArchive() {
		$delete = $this->request->dataPost();
		$params = new CommentEdit($delete);
		$comment = \Comment::Archive($params);

		$result = [
			'comment_id' => $comment->getCommentId(),
		];

		$this->response->set($result);
	}
}
