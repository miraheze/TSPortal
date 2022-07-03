<?php

namespace App\Providers;

use App\Events\DPANew;
use App\Events\InvestigationClosed;
use App\Events\InvestigationNew;
use App\Events\ReportNew;
use App\Listeners\SendDiscordNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
	/**
	 * The event listener mappings for the application.
	 *
	 * @var array
	 */
	protected $listen = [
		DPANew::class              => [ SendDiscordNotification::class ],
		InvestigationNew::class    => [ SendDiscordNotification::class ],
		InvestigationClosed::class => [ SendDiscordNotification::class ],
		ReportNew::class           => [ SendDiscordNotification::class ]
	];

	/**
	 * Register any events.
	 *
	 * @return void
	 */
	public function boot()
	{
	}
}
