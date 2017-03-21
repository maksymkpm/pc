<?php
require_once('curl.php');

$methods = [
	'auth' => [
		'url' => 'http://localhost.api.com/1.0/member/auth',
		'method' => 'POST',
		'param' => [
			'vk_member_id' => 666,
			'origin' => 'vk'
		],
	],
	/*
	'get' => [
		'url' => 'http://localhost.api.com/1.0/member',
		'method' => 'GET',
		'param' => [
			'member_id' => rand(1,6)
		],
	],
	
	'create' => [
		'url' => 'http://localhost.api.com/1.0/member',
		'method' => 'POST',
		'param' => [
			'gender' => 'man',
			'bdate' => '1991-01-01',
			'origin' => 'vk',
			'username' => rand(1,6),
			'password' => 'coloclo@'
		],
	],
*/
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