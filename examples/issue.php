<?php
require_once('curl.php');

$methods = [
	'get' => [
		'url' => 'http://localhost.api.com/1.0/issue',
		'method' => 'GET',
		'param' => [
			'issue_id' => rand(1,4),
		],
	],
	'create' => [
		'url' => 'http://localhost.api.com/1.0/issue',
		'method' => 'POST',
		'param' => [
			'member_id' => rand(100,4544),
			'title' => 'сфысфыф',
			'description' => 'descriptiondescription',
			'class_id' => rand(1,7),
			'category_id' => rand(1,30),
			'object_id' => rand(1,30),
			'subject_id' => rand(1,30),
		],
	],
	'edit' => [
		'url' => 'http://localhost.api.com/1.0/issue',
		'method' => 'PUT',
		'param' => [
			'issue_id' => rand(1,8),
			'title' => 'уукпуукпу!!!titlecolo',
			'class_id' => rand(1,30),
			'status' => 'archived'
		],
	],
	
	'classification' => [
		'url' => 'http://localhost.api.com/1.0/issue-class',
		'method' => 'GET',
		'param' => [
		],
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