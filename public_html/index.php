<?php
/**
 * RESTful API server input point for all requests and all versions
 *
 * Resolve version and run requested version of server
 *
 * Global constants:
 *        REQUEST_START_TIME - time of the request start
 *        REQUEST_START_MEMORY - memory usage at the beginning of the request
 *        SERVER_ROOT - path to the root folder of requested version of server
 */
use rest\server;

define('REQUEST_START_TIME', microtime(true));
define('REQUEST_START_MEMORY', memory_get_usage());

require_once __DIR__ . '/../vendor/autoload.php';
require_once '../setup.php';

$environment = Environment::get();
if (Environment::isDevelopment()) {
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
}

config::add_source(__DIR__ . '/../config');
config::add_source(__DIR__ . '/../config/' . strtolower($environment));

try {
	
	if (empty(rest\request::instance()->version)) {

		rest\response::errorNotFound();
	}

	$server_root = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'api' . DIRECTORY_SEPARATOR . 'v' . str_replace('.', '_', rest\request::instance()->version);
	server::init($server_root);

	if (!is_file($server_root . '/bootstrap.php')) {
		rest\response::error500('File bootstrap.php is not exist');
	}

	require($server_root . '/bootstrap.php');
} catch (Exception $e) {
	if (Environment::isDevelopment()) {
		$result = [
			'type' => 'system-error',
			'message' => $e->getMessage(),
		];

		$result['trace'] = $e->getTrace();

		header('Content-Type: application/json', true, 500);

		echo json_encode($result);
	}
}
