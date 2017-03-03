<?php

use rest\request;
use rest\server;

// Set application timezone or comment this line for using apache timezone
date_default_timezone_set('UTC');

//server::bug_tracker_disable(); // if bugtracker is enabled, all exceptions and PHP errors will be send to bugtracker
//server::debug_mode_disable(); // debug mode disables caching of routing table
Server::debugModeEnable();
// by default the server will support GET, POST, PUT, DELETE
Server::setSupportedMethods(request::HTTP_GET, request::HTTP_POST, request::HTTP_DELETE, request::HTTP_PUT);

// Setup routing table
require 'routing.php';

// Run the server and echo the result
try {
	$response = Server::run();
} catch (Exception $e) {
	echo $e->getMessage();
}
if (Server::isDebugModeEnabled()) {
	// Output in headers debug information about response time and memory usage
	$format_memory = function($value) {
		if ($value < 1024) {
			$value = $value . ' B';
		} else if ($value < 1048576) {
			$value = sprintf('%01.2F KiB', round($value / 1024, 2));
		} else {
			$value = sprintf('%01.2F MiB', round($value / 1048576, 2));
		}

		return $value;
	};

	$response->setHeaders([
		'X-Debug-Time-Total' => sprintf('%01.3F sec.', round(microtime(true) - REQUEST_START_TIME, 3)),
		'X-Debug-Time-Action' => sprintf('%01.3F sec.', \rest\controller::instance()->getActionRunTime()),
		'X-Debug-Memory' => $format_memory(memory_get_usage() - REQUEST_START_MEMORY),
		'X-Debug-Peak' => $format_memory(memory_get_peak_usage()),
	]);
}

// send headers and output response
$response->send();
