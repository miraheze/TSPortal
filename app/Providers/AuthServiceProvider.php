<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider {
	/**
	 * The policy mappings for the application.
	 *
	 * @var array
	 */
	protected $policies = [
		'App\Models\Appeals'       => 'App\Policies\AppealPolicy',
		'App\Models\DPA'           => 'App\Policies\DPAPolicy',
		'App\Models\IAL'           => 'App\Policies\IALPolicy',
		'App\Models\Investigation' => 'App\Policies\InvestigationPolicy',
		'App\Models\Report'        => 'App\Policies\ReportPolicy',
		'App\Models\User'          => 'App\Policies\UserPolicy',
	];

	/**
	 * Register any authentication / authorization services.
	 *
	 * @return void
	 */
	public function boot() {
		$this->registerPolicies();

		Gate::define( 'ts', static function ( User $user ) {
			return $user->hasFlag( 'ts' );
		} );

		Gate::define( 'user-manager', static function ( User $user ) {
			return $user->hasFlag( 'user-manager' );
		} );
	}
}
