<?php

declare(strict_types=1);

namespace App\Providers;

use App\Events\DPANew;
use App\Events\InvestigationClosed;
use App\Events\InvestigationNew;
use App\Events\ReportNew;
use App\Listeners\SendWebhookNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        DPANew::class => [SendWebhookNotification::class],
        InvestigationNew::class => [SendWebhookNotification::class],
        InvestigationClosed::class => [SendWebhookNotification::class],
        ReportNew::class => [SendWebhookNotification::class],
    ];

    /**
     * Register any events.
     */
    public function boot(): void {}
}
