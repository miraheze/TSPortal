<?php

return [

	/*
	|--------------------------------------------------------------------------
	| Default Mailer
	|--------------------------------------------------------------------------
	|
	| This option controls the default mailer that is used to send any email
	| messages sent by your application. Alternative mailers may be setup
	| and used as needed; however, this mailer will be used by default.
	|
	*/

	'default' => env( 'MAIL_MAILER', 'smtp' ),

	/*
	|--------------------------------------------------------------------------
	| Mailer Configurations
	|--------------------------------------------------------------------------
	|
	| Here you may configure all the mailers used by your application plus
	| their respective settings.
	|
	| Supported: "smtp", "sendmail"
	|
	*/

	'mailers' => [
		'smtp'     => [
			'transport'  => 'smtp',
			'host'       => env( 'MAIL_HOST', 'ssl://smtp-relay.gmail.com' ),
			'port'       => env( 'MAIL_PORT', 465 ),
			'encryption' => env( 'MAIL_ENCRYPTION', 'tls' ),
			'username'   => env( 'MAIL_USERNAME' ),
			'password'   => env( 'MAIL_PASSWORD' ),
			'timeout'    => null,
			'auth_mode'  => null,
		],
		'sendmail' => [
			'transport' => 'sendmail',
			'path'      => '/usr/sbin/sendmail -bs',
		],
	],

	/*
	|--------------------------------------------------------------------------
	| Global "From" Address
	|--------------------------------------------------------------------------
	|
	| You may wish for all e-mails sent by your application to be sent from
	| the same address. Here, you may specify a name and address that is
	| used globally for all e-mails that are sent by your application.
	|
	*/

	'from' => [
		'address' => env( 'MAIL_FROM_ADDRESS', 'hello@example.com' ),
		'name'    => env( 'MAIL_FROM_NAME', 'Example' ),
	],

	/*
	|--------------------------------------------------------------------------
	| Markdown Mail Settings
	|--------------------------------------------------------------------------
	|
	| If you are using Markdown based email rendering, you may configure your
	| theme and component paths here, allowing you to customize the design
	| of the emails.
	|
	*/

	'markdown' => [
		'theme' => 'default',

		'paths' => [
			resource_path( 'views/vendor/mail' ),
		],
	],

];
