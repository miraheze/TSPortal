<?php

use App\Providers\AppServiceProvider;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpFoundation\Request;

return Application::configure( basePath: dirname( __DIR__ ) )
	->withProviders( [
		AppServiceProvider::class,
	] )
	->withRouting(
		web: __DIR__ . '/../routes/web.php',
		api: __DIR__ . '/../routes/api.php',
		commands: __DIR__ . '/../routes/console.php',
		health: '/up',
	)
	->withMiddleware( static function ( Middleware $middleware ): void {
		$middleware->throttleApi();
		$middleware->redirectTo(
			guests: static fn (): string => route( 'login' ),
			users: '/',
		);

		$middleware->trustProxies(
			headers:
				Request::HEADER_X_FORWARDED_FOR |
				Request::HEADER_X_FORWARDED_HOST |
				Request::HEADER_X_FORWARDED_PORT |
				Request::HEADER_X_FORWARDED_PROTO |
				Request::HEADER_X_FORWARDED_AWS_ELB
		);
	} )
	->withExceptions( static function ( Exceptions $exceptions ): void {
		//
	} )
	->create();
