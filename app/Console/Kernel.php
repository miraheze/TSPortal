<?php

namespace App\Console;

use App\Schedules\IALScheduler;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
	/**
	 * Kernel Scheduler
	 *
	 * @param Schedule $schedule
	 *
	 * @return void
	 */
	protected function schedule( Schedule $schedule )
	{
		$schedule->call( new IALScheduler() )->dailyAt( '00:00' )->description( 'Daily Discord Disgest for IAL' );
	}
}
