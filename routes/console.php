<?php

use App\Schedules\IALScheduler;
use Illuminate\Support\Facades\Schedule;

Schedule::call( new IALScheduler() )
	->dailyAt( '00:00' )
	->description( 'Daily Webhook Disgest for IAL' );