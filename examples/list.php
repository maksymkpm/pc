<?php
require_once('curl.php');

$methods = [
	'get' => [
		'url' => 'http://localhost.api.com/1.0/issue/list',
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