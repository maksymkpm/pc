<?php
require_once('curl.php');

$methods = [
	'0' => [
		'url' => 'http://localhost.api.com/1.0/issue',
		'method' => 'GET',
	],
	'1' => [
		'url' => 'http://localhost.api.com/1.0/issue',
		'method' => 'POST',
	],
	'2' => [
		'url' => 'http://localhost.api.com/1.0/issue',
		'method' => 'PUT',
	],
];

foreach ($methods as $key => $method) {
	switch ($method['method']) {
		case 'GET': 
			$param = [
				'issue_id' => rand(1,8),
			];
		break;
		case 'POST': 
			$param = [
				'member_id' => 22,
				'title' => 'сфысфыф',
				'description' => 'descriptiondescription',
				'class_id' => 1,
				'category_id' => 2,
				'object_id' => 2,
				'subject_id' => 1,
			];
		break;
		case 'PUT': 
			$param = [
				'issue_id' => rand(1,8),
				'title' => 'уукпуукпу!!!titlecolo',
				'class_id' => 21,
				'status' => 'archived'
			];
		break;
	}

	try {
	$response = curl::request($method['method'], $method['url'])
			  ->set_timeout(60)
			  ->set_header('Authorization', 'Bearer mF9B5f41JqM')
			  ->set_parameter($param)
			  ->set_attempts(1)
			  ->execute();
	} catch (CurlException $e) {
		$response = $e;
	}

	var_dump($method, $response);
}