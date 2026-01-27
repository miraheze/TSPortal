<?php

declare(strict_types=1);
use App\Providers\AppServiceProvider;
use App\Providers\AuthServiceProvider;
use App\Providers\EventServiceProvider;
use App\Providers\RouteServiceProvider;

return [
	AppServiceProvider::class,
	AuthServiceProvider::class,
	EventServiceProvider::class,
	RouteServiceProvider::class,
];
