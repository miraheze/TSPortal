<?php

return [

	/*
	|--------------------------------------------------------------------------
	| Application Name
	|--------------------------------------------------------------------------
	|
	| This value is the name of your application. This value is used when the
	| framework needs to place the application's name in a notification or
	| any other location as required by the application or its packages.
	|
	*/

	'name' => 'Miraheze TSPortal',

	/*
	|--------------------------------------------------------------------------
	| Application Version
	|--------------------------------------------------------------------------
	|
	| This is the current version of the software.
	|
	 */

	'version' => 29,

	/*
	|--------------------------------------------------------------------------
	| Application Environment
	|--------------------------------------------------------------------------
	|
	| This value determines the "environment" your application is currently
	| running in. This may determine how you prefer to configure various
	| services the application utilizes. Set this in your ".env" file.
	|
	*/

	'env' => env( 'APP_ENV', 'production' ),

	/*
	|--------------------------------------------------------------------------
	| Application Debug Mode
	|--------------------------------------------------------------------------
	|
	| When your application is in debug mode, detailed error messages with
	| stack traces will be shown on every error that occurs within your
	| application. If disabled, a simple generic error page is shown.
	|
	*/

	'debug' => (bool)env( 'APP_DEBUG', false ),

	/*
	|--------------------------------------------------------------------------
	| Application URL
	|--------------------------------------------------------------------------
	|
	| This URL is used by the console to properly generate URLs when using
	| the Artisan command line tool. You should set this to the root of
	| your application so that it is used when running Artisan tasks.
	|
	*/

	'url' => env( 'APP_URL', 'http://localhost' ),

	/*
	|--------------------------------------------------------------------------
	| Application Timezone
	|--------------------------------------------------------------------------
	|
	| Here you may specify the default timezone for your application, which
	| will be used by the PHP date and date-time functions.
	|
	*/

	'timezone' => 'UTC',

	/*
	|--------------------------------------------------------------------------
	| Application Locale Configuration
	|--------------------------------------------------------------------------
	|
	| The application locale determines the default locale that will be used
	| by the translation service provider.
	|
	*/

	'locale' => 'en',

	/*
	|--------------------------------------------------------------------------
	| Application Fallback Locale
	|--------------------------------------------------------------------------
	|
	| The fallback locale determines the locale to use when the current one
	| is not available.
	|
	*/

	'fallback_locale' => 'en',

	/*
	|--------------------------------------------------------------------------
	| Encryption Key
	|--------------------------------------------------------------------------
	|
	| This key is used by to Illuminate encrypter service and should be set
	| to a random, 32 character string, otherwise these encrypted strings
	| will not be safe.
	|
	*/

	'key' => env( 'APP_KEY' ),

	'cipher' => 'AES-256-CBC',

	/*
	|--------------------------------------------------------------------------
	| DPA Rejections
	|--------------------------------------------------------------------------
	|
	| This is a list of rejection reasons for a DPA request.
	|
	*/
	'rejectDPA' => [
		'lawful',
		'contract',
		'public',
		'legal',
		'unfoundly',
	],

	/*
	|--------------------------------------------------------------------------
	| Investigation Topics
	|--------------------------------------------------------------------------
	|
	| List of reasons for an investigation to be launched
	|
	*/
	'investigationTopics' => [
		'copyright',
		'cp',
		'dp',
		'protection',
		'terrorism',
		'low',
		'other',
	],

	/*
	|--------------------------------------------------------------------------
	| Recommendations
	|--------------------------------------------------------------------------
	|
	| List of recommendations that can be selected from an investigation
	|
	*/
	'recommendations' => [
		'ban',
		'partial',
		'rights',
		'remove',
		'protect',
		'unknown',
	],

	/*
	|--------------------------------------------------------------------------
	| Events
	|--------------------------------------------------------------------------
	|
	| All events that can be recorded as part of a users journey through
	| TSPortal, except logins and creations.
	|
	*/
	'events' => [
		'nd' => [
			'block',
			'checkuser',
			'delete',
			'oversight',
			'protect',
			'rights',
			'wikiclose',
			'wikidelete',
		],
		'd' => [
			'checkuser',
		],
		'ban' => [
			'partial',
			'full',
		],
		'appeal' => [
			'recv',
		],
	],

	/*
	|--------------------------------------------------------------------------
	| Report Topics
	|--------------------------------------------------------------------------
	|
	| List of topics that can be used for reporting.
	|
	*/
	'reportTopics' => [
		'content' => [
			'license',
			'sexual-gore',
			'cp',
			'selfharm',
		],
		'people' => [
			'harassment',
			'terrorism',
			'other',
		],
	],

	/*
	|--------------------------------------------------------------------------
	| Legislation
	|--------------------------------------------------------------------------
	|
	| List of legislation we might use in investigations. This serves purely as
	| a cheat sheet currently. Additional logic may use this in the future.
	|
	*/
	'legislation' => [
		'Animal Cruelty' => 'S.8(3) Animal Welfare Act 2006',
		'Belong (or Profess) to a Proscribed Terrorist Organisation' => 'S.11 Terrorism Act 2000',
		'Bomb Hoax' => 'S.114(2) Anti-terrorism, Crime and Security Act 2001',
		'Child Pornography' => 'S.1 Protection of Children Act 1978',
		'Complicity in Suicide' => 'S.2 Suicide Act 1961',
		'Computer - Unauthorised Act' => 'S.3 Computer Misuse Act 1990',
		'Computer - Unauthorised Access' => 'S.1 Computer Misuse Act 1990',
		'Copyright Distribution' => 'S.107 Copyright, Designs and Patents Act 1988',
		'Encouragement of Terrorism' => 'S.1 Terrorism Act 2006',
		'Extreme Pornography' => 'S.63 Criminal Justice and Immigration Act 2008',
		'Failure to Disclose Terrorist Activity' => 'S.38(b) Terrorism Act 2000',
		'Harassment' => 'S.2 Protection from Harassment Act 1997',
		'Human Trafficking' => 'S.2 Modern Slavery Act 2015',
		'Malicious Communications' => 'S.1 Malicious Communications Act 1988',
		'Obtain/Disclose/Procure Personal Data' => 'S.170(1) Data Protection Act 2018',
		'Preparation of Terrorism Activity' => 'S.5 Terrorism Act 2006',
		'Revenge Pornography' => 'S.33 Criminal Justice and Courts Act 2015',
		'Threats to Kill' => 'S.16 Offences Against the Person Act 1861',
	],

	/*
	|--------------------------------------------------------------------------
	| At Risk Emails
	|--------------------------------------------------------------------------
	|
	| Where to send email alerts to for At Risk identified reports.
	|
	*/
	'atrisk' => env( 'ALERT_EMAIL' ),

	/*
	|--------------------------------------------------------------------------
	| Discord Webhook
	|--------------------------------------------------------------------------
	|
	| Where to send discord alerts for new models and major actions.
	|
	*/
	'discordhook' => env( 'DISCORD_WEBHOOK' ),

	/*
	|--------------------------------------------------------------------------
	| Mattermost Webhook
	|--------------------------------------------------------------------------
	|
	| Where to send mattermost alerts for new models and major actions.
	|
	*/
	'mattermosthook' => env( 'MATTERMOST_WEBHOOK' ),

	/*
	|--------------------------------------------------------------------------
	| Web Proxy
	|--------------------------------------------------------------------------
	|
	| URL for HTTP cURL requests if a web proxy is required.
	|
	*/
	'proxy' => env( 'WEB_PROXY' ),

	/*
	|--------------------------------------------------------------------------
	| Appeal Avenues
	|--------------------------------------------------------------------------
	|
	| Config for handling all appeal avenues by type, boolean determines whether
	| the weight a factor has on an appeal's recommended outcome.
	|
	*/
	'appeals' => [
		'not-appropriate' => [
			'explained' => [
				'yes' => 0,
				'no' => 1,
			],
			'exhausted' => [
				'yes' => 1,
				'no' => -1,
			],
			'community' => [
				'yes' => 0,
				'no' => 1,
			],
			'purview' => [
				'yes' => -1,
				'no' => 1,
			],
		],
		'new-information' => [
			'relevant' => [
				'yes' => 0,
				'no' => 1,
			],
			'contradict' => [
				'yes' => -1,
				'no' => 1,
			],
			'sanctions' => [
				'yes' => 1,
				'no' => -1,
			],
		],
		'impossible-outcome' => [
			'disregard' => [
				'yes' => -1,
				'no' => 1,
			],
			'undisputed' => [
				'yes' => 1,
				'no' => -1,
			],
			'justify' => [
				'yes' => 1,
				'no' => -1,
			],
			'follow' => [
				'yes' => 1,
				'no' => -1,
			],
			'lesser' => [
				'yes' => -1,
				'no' => 1,
			],
		],
	],

	/*
	|--------------------------------------------------------------------------
	| API Keys
	|--------------------------------------------------------------------------
	|
	| Here we can maintain a list of API keys that are valid for write actions.
	|
	*/

	'writekey' => env( 'WRITE_KEY' ),
];
