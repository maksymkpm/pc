<?php
namespace api\v1_0\controller;
use AuthenticationException;
use \rest\controller;
use rest\response;
use rest\server;

/**
 * Base class for all controllers in this version of the API server.
 * It can contain some global logic, what is actual for all controllers.
 * 		- global prepare logic before executing action
 * 		- global post processing logic after executing action
 * 		- global helper functions
 *
 * NOTE: do not update the common class \rest\controller, use this class inside every versions of the server
 */
abstract class BaseController extends controller {
	/**
	 * Actions allowed without token
	 * @var array
	 */
	protected $no_token_actions = [];
	/**
	 * @var \ManageListRequest
	 */
	protected $manageListRequest;

	/**
	 * @return string
	 */
	protected function getControllerName(): string {
		$name = substr(get_class($this), 10);
		$name[0] = strtolower($name[0]);

		return $name;
	}

	/**
	 * @inheritdoc
	 */
	public function execute($action, array $parameters, $content_type) {
		try {
			return parent::execute($action, $parameters, $content_type);
		} catch (\ValidationException $e) {
			$messages = [];

			foreach($e->getMessages() as $field => $message){
				$messages[] = [
					'field' => $field,
					'message' => $message
				];
			}

			$result = [
				'type' => 'validation',
				'messages' => $messages
			];

			if (server::isDebugModeEnabled()) {
				$result['trace'] = $e->getTraceAsString();
			}

			header('Content-Type: application/json', true, 400);

			$this->response->set($result);
		} catch (\AuthenticationException $e){
			$result = [
				'type' => 'authentication',
				'message' => $e->getMessage()
			];

			header('Content-Type: application/json', true, $e->getCode());
			$this->response->set($result);
		}catch (\NotFoundHttpException $e){
			$result = [
				'type' => 'not-found',
				'message' => $e->getMessage()
			];

			header('Content-Type: application/json', true, 404);
			$this->response->set($result);
		}catch (\RuntimeException $e) {
			$result = [
				'type' => 'system-error',
				'message' => $e->getMessage(),
			];

			$result['trace'] = $e->getTrace();

			header('Content-Type: application/json', true, 500);
			$this->response->set($result);
		} catch (\DatabaseException $e) {
			$result = [
				'type' => 'system-error',
				'message' => $e->getMessage()
					. "\n\nSQL: " . $e->getQuery()
					. "\n\nBinds: " . print_r($e->getBinds(), true)
					. "\n\nTrace: " . $e->getTrace(),
			];

			header('Content-Type: application/json', true, 500);
			$this->response->set($result);
		} catch (\Exception $e) {
			response::error500();

			$result = [
				'type' => 'system-error',
				'message' => $e->getMessage(),
				'trace' => $e->getTrace()
			];

			header('Content-Type: application/json', true, 500);
			$this->response->set($result);
		}

		return $this->response;
	}

	/**
	 *
	 * @param array $result
	 */
	protected function response(array $result = null) {
		$result = $result ?? new \stdClass();

		header('Content-Type: application/json', true, 200);

		$this->response
			->set($result);
	}

	protected function runBeforeAction() {
		$postData = $this->request->dataPost();
	}

	protected function runAfterAction() {}
}
