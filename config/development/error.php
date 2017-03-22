<?php
return [
	//Authentication exceptions
	'TOKEN_NOT_EXIST' => [
			'code' => 410,
			'ru' => '',
			'en' => 'Provided token is not verified.',
		],

	'TOKEN_EXPIRED' => [
			'code' => 411,
			'ru' => '',
			'en' => 'Invalid access token.',
		],

	'default' => [
		'ru' => '',
		'en' => 'Some error occurred.',
		],

	];
