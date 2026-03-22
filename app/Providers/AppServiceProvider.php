<?php

declare( strict_types = 1 );

namespace App\Providers;

use App\Events\AppealNew;
use App\Events\DPANew;
use App\Events\InvestigationClosed;
use App\Events\InvestigationNew;
use App\Events\InvestigationReopened;
use App\Events\ReportNew;
use App\Listeners\SendWebhookNotification;
use App\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Override;

class AppServiceProvider extends ServiceProvider
{
	/**
	 * Register any application services.
	 */
	#[Override]
	public function register(): void
	{
		//
	}

	/**
	 * Bootstrap any application services.
	 */
	public function boot(): void
	{
		RateLimiter::for( 'api', static function ( Request $request ): Limit {
			return Limit::perMinute( 60 )->by( $request->user()?->id ?: $request->ip() );
		} );

		Gate::define( 'ts', static function ( User $user ): bool {
			return $user->hasFlag( 'ts' );
		} );

		Gate::define( 'user-manager', static function ( User $user ): bool {
			return $user->hasFlag( 'user-manager' );
		} );

		Event::listen(
			[
				AppealNew::class,
				DPANew::class,
				InvestigationClosed::class,
				InvestigationNew::class,
				InvestigationReopened::class,
				ReportNew::class,
			],
			SendWebhookNotification::class
		);

		Paginator::useBootstrapFive();
	}
}
