<?php

return [

	/*
	|--------------------------------------------------------------------------
	| Default Filesystem Disk
	|--------------------------------------------------------------------------
	|
	| Here you may specify the default filesystem disk that should be used
	| by the framework. The "local" disk, as well as a variety of cloud
	| based disks are available to your application. Just store away!
	|
	*/

	'default' => env( 'FILESYSTEM_DISK', 'local' ),

	/*
	|--------------------------------------------------------------------------
	| Filesystem Disks
	|--------------------------------------------------------------------------
	|
	| Here you may configure as many filesystem "disks" as you wish, and you
	| may even configure multiple disks of the same driver.
	|
	| Supported Drivers: "local"
	|
	*/

	'disks' => [
		'local'  => [
			'driver' => 'local',
			'root' => storage_path( 'app/private' ),
			'serve' => true,
			'throw' => false,
			'report' => false,
		],
		'public' => [
			'driver' => 'local',
			'root' => storage_path( 'app/public' ),
			'url' => rtrim( env( 'APP_URL' ), '/' ) . '/storage',
			'visibility' => 'public',
			'throw' => false,
			'report' => false,
		],
	],

	/*
	|--------------------------------------------------------------------------
	| Symbolic Links
	|--------------------------------------------------------------------------
	|
	| Here you may configure the symbolic links that will be created when the
	| `storage:link` Artisan command is executed. The array keys should be
	| the locations of the links and the values should be their targets.
	|
	*/

	'links' => [
		public_path( 'storage' ) => storage_path( 'app/public' ),
	],

];
