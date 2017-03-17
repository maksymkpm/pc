<?php
require_once('curl.php');

$methods = [	
	'IssueCreate' => [
		'url' => 'http://localhost.api.com/1.0/feedback/issue',
		'method' => 'POST',
		'param' => [
			'comment_id' => 1,
			'member_id' => 22,
		],
	],
'CommentCreate' => [
		'url' => 'http://localhost.api.com/1.0/feedback/comment',
		'method' => 'POST',
		'param' => [
			'issue_id' => 1,
			'member_id' => 22,
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