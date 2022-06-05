<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
	/**
	 * The event listener mappings for the application.
	 *
	 * @var array
	 */
	protected $listen = []; // TODO: Email notifications for ts@

	/**
	 * Register any events.
	 *
	 * @return void
	 */
	public function boot()
	{
	}
}
