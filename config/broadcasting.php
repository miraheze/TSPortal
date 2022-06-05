<?php

return [

	/*
	|--------------------------------------------------------------------------
	| Default Broadcaster
	|--------------------------------------------------------------------------
	|
	| This option controls the default broadcaster that will be used by the
	| framework when an event needs to be broadcast. You may set this to
	| any of the connections defined in the "connections" array below.
	|
	| Supported: "redis", "null"
	|
	*/

	'default' => env( 'BROADCAST_DRIVER', 'null' ),

	/*
	|--------------------------------------------------------------------------
	| Broadcast Connections
	|--------------------------------------------------------------------------
	|
	| Here you may define all the broadcast connections that will be used
	| to broadcast events to other systems or over websockets.
	|
	*/

	'connections' => [
		'redis' => [
			'driver'     => 'redis',
			'connection' => 'default',
		],
		'null'  => [
			'driver' => 'null',
		],

	],

];
