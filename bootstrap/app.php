<?php

declare( strict_types = 1 );

use App\Providers\AppServiceProvider;
use App\Schedules\IALScheduler;
use Illuminate\Console\Scheduling\Schedule;
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
	->withSchedule( static function ( Schedule $schedule ): void {
		$schedule->call( new IALScheduler )
			->dailyAt( '00:00' )
			->description( 'Daily Webhook Disgest for IAL' );
	} )
	->withExceptions( static function ( Exceptions $exceptions ): void {
		//
	} )
	->create();
