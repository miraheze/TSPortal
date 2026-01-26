<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register()
	{
		//
	}

	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		RateLimiter::for( 'api', function ( Request $request ) {
			return Limit::perMinute( 60 )->by( $request->user()?->id ?: $request->ip() );
		} );

		Gate::define( 'ts', function ( User $user ) {
			return $user->hasFlag( 'ts' );
		} );

		Gate::define( 'user-manager', function ( User $user ) {
			return $user->hasFlag( 'user-manager' );
		} );

		Paginator::useBootstrap();
	}
}
