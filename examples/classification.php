<?php
require_once('curl.php');

$methods = [
	'classification' => [
		'url' => 'http://localhost.api.com/1.0/classification',
		'method' => 'GET',
		'param' => [],
	],
	'classes' => [
		'url' => 'http://localhost.api.com/1.0/classification/classes',
		'method' => 'GET',
		'param' => [],
	],
	'objects' => [
		'url' => 'http://localhost.api.com/1.0/classification/objects',
		'method' => 'GET',
		'param' => [],
	],
	'subjects' => [
		'url' => 'http://localhost.api.com/1.0/classification/subjects',
		'method' => 'GET',
		'param' => [],
	],
	'categories' => [
		'url' => 'http://localhost.api.com/1.0/classification/categories',
		'method' => 'GET',
		'param' => [],
	],
];

foreach ($methods as $key => $method) {
	try {
	$response = curl::request($method['method'], $method['url'])
			  ->set_timeout(60)
			  ->set_header('Authorization', 'Bearer mF9B5f41JqM')
			  ->set_parameter($method['param'])
			  ->set_attempts(1)
			  ->execute();
	} catch (CurlException $e) {
		$response = $e;
	}

	var_dump($method, $response);
}