<?php
require_once('curl.php');

$methods = [

	'create' => [
		'url' => 'http://localhost.api.com/1.0/member',
		'method' => 'POST',
		'param' => [
			//'gender' => 'man',
			//'bdate' => '1991-01-01',
			'profile' => 'vk',
			'username' => rand(1,6)
		],
	],
	
	'auth' => [
		'url' => 'http://localhost.api.com/1.0/member/auth',
		'method' => 'POST',
		'param' => [
			'vk_member_id' => 666,
			'profile' => 'vk'
		],
	],

	'get' => [
		'url' => 'http://localhost.api.com/1.0/member',
		'method' => 'GET',
		'param' => [
			'member_id' => 5
		],
	],
	
	//can be without params
	'IssueList' => [
		'url' => 'http://localhost.api.com/1.0/member/issue/list',
		'method' => 'GET',
		'param' => [
			'status' => 'new',
			
		],
	],
	
	'follow_issue' => [
		'url' => 'http://localhost.api.com/1.0/member/follow/issue',
		'method' => 'POST',
		'param' => [
			'issue_id' => rand(1,5),
		],
	],
	
	'follow_member' => [
		'url' => 'http://localhost.api.com/1.0/member/follow/member',
		'method' => 'POST',
		'param' => [
			'member_id' => rand(1,5),
		],
	],
	
	'stop_follow_issue' => [
		'url' => 'http://localhost.api.com/1.0/member/stopfollow/issue',
		'method' => 'POST',
		'param' => [
			'issue_id' => rand(1,5),
		],
	],
	
	'stop_follow_member' => [
		'url' => 'http://localhost.api.com/1.0/member/stopfollow/member',
		'method' => 'POST',
		'param' => [
			'member_id' => rand(1,5),
		],
	],

];

foreach ($methods as $key => $method) {
	try {
	$response = curl::request($method['method'], $method['url'])
			  ->set_timeout(60)
			  ->set_header('Authorization', 'Bearer M$2y$10$TEEzMzbaOLAJHX4DJfxAz.VaaAhsWspRV.P6RSo.R1Dl09kMLti4O')
			  ->set_parameter($method['param'])
			  ->set_attempts(1)
			  ->execute();
	} catch (CurlException $e) {
		$response = $e->getMessage();
	}

	var_dump($method, $response);
}