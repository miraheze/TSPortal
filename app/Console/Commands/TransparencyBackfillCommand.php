<?php

declare( strict_types = 1 );

namespace App\Console\Commands;

use App\Models\TransparencyReport;
use App\Support\TransparencyBackfill;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

/**
 * Fills the transparency counters from existing records.
 */
#[Description( 'Build the transparency counters from existing records' )]
#[Signature( 'transparency:backfill {--force : Replace counters that already exist} {--include-published : Also rewrite published periods}' )]
class TransparencyBackfillCommand extends Command
{
	/**
	 * Execute the console command.
	 */
	public function handle( TransparencyBackfill $backfill ): int
	{
		if ( TransparencyReport::query()->exists() && !$this->option( 'force' ) ) {
			$this->error( 'Counters already exist. Use --force to rebuild them.' );
			return self::FAILURE;
		}

		$this->line( 'Wrote ' . $backfill->run( (bool)$this->option( 'include-published' ) ) . ' counters.' );
		return self::SUCCESS;
	}
}
