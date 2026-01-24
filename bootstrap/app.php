<?php

use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpFoundation\Request as RequestAlias;

return Application::configure( basePath: dirname( __DIR__ ) )
	->withRouting(
		web: __DIR__ . '/../routes/web.php',
		api: __DIR__ . '/../routes/api.php',
		commands: __DIR__ . '/../routes/console.php',
		health: '/up',
	)
	->withMiddleware( function ( Middleware $middleware ): void {
		$middleware->throttleApi();
		$middleware->redirectTo(
			guests: route( 'login' ),
			users: RouteServiceProvider::HOME,
		);

		$middleware->trustProxies(
			headers:
				RequestAlias::HEADER_X_FORWARDED_FOR |
				RequestAlias::HEADER_X_FORWARDED_HOST |
				RequestAlias::HEADER_X_FORWARDED_PORT |
				RequestAlias::HEADER_X_FORWARDED_PROTO |
				RequestAlias::HEADER_X_FORWARDED_AWS_ELB
		);
	} )
	->withExceptions( function ( Exceptions $exceptions ): void {
		//
	} )
	->create();
