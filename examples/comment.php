<?php
require_once('curl.php');

$methods = [	
	'create' => [
		'url' => 'http://localhost.api.com/1.0/comment',
		'method' => 'POST',
		'param' => [
			'issue_id' => 1,
			'member_id' => 22,
			'message' => 'messagemessagemessagemessage',
		],
	],

	'get' => [
		'url' => 'http://localhost.api.com/1.0/comment',
		'method' => 'GET',
		'param' => [
			'comment_id' => 2,
		],
	],
	
	'edit' => [
		'url' => 'http://localhost.api.com/1.0/comment',
		'method' => 'PUT',
		'param' => [
			'comment_id' => 3,
			'message' => 'уукпуукпу!!!titlecolo',
		],
	],

	'delete' => [
		'url' => 'http://localhost.api.com/1.0/comment/delete',
		'method' => 'PUT',
		'param' => [
			'comment_id' => 2,
			'issue_id' => 3,
		],
	],
	
	'archive' => [
		'url' => 'http://localhost.api.com/1.0/comment/archive',
		'method' => 'PUT',
		'param' => [
			'comment_id' => 5,
			'issue_id' => 2,
		],
	],

	'publish' => [
		'url' => 'http://localhost.api.com/1.0/comment/publish',
		'method' => 'PUT',
		'param' => [
			'comment_id' => 6,
			'issue_id' => 1,
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
	} catch (Exception $e) {
		$response = $e;
	}

	var_dump($method, $response);
}