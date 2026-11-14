<?php

declare( strict_types = 1 );

namespace App\Support;

use App\Models\Appeal;
use App\Models\DPA;
use App\Models\Investigation;
use App\Models\Report;
use App\Models\TransparencyReport;
use App\Models\UserEvent;
use Carbon\CarbonImmutable;
use function array_chunk;
use function array_values;
use function count;
use function in_array;

/**
 * Rebuilds the monthly counters from records that already exist.
 */
class TransparencyBackfill
{
	/**
	 * @var array<string, array<string, mixed>>
	 */
	private array $cells = [];

	/**
	 * Replaces the counters with totals worked out from existing records.
	 *
	 * Months in a published period are left alone unless told otherwise.
	 */
	public function run( bool $includePublished = false ): int
	{
		$this->cells = [];

		foreach ( Report::query()->toBase()->get( [ 'type', 'investigation', 'dpa', 'created', 'reviewed' ] ) as $report ) {
			$this->add( 'report', $report->type, 'requested', $report->created );
			if ( $report->reviewed !== null ) {
				$this->add( 'report', $report->type, 'completed', $report->reviewed, TransparencyReport::reportOutcome( $report->investigation, $report->dpa ) );
			}
		}

		foreach ( Investigation::query()->toBase()->get( [ 'type', 'recommendation', 'created', 'closed' ] ) as $investigation ) {
			$this->add( 'investigation', $investigation->type, 'requested', $investigation->created );
			if ( in_array( $investigation->recommendation, TransparencyReport::recommendationKeys(), true ) ) {
				$this->add( 'recommendation', $investigation->recommendation, 'requested', $investigation->created );
			}

			if ( $investigation->closed !== null ) {
				$this->add( 'investigation', $investigation->type, 'completed', $investigation->closed );
			}
		}

		foreach ( Appeal::query()->toBase()->get( [ 'type', 'outcome', 'created', 'reviewed' ] ) as $appeal ) {
			$this->add( 'appeal', $appeal->type, 'requested', $appeal->created );
			if ( $appeal->reviewed !== null ) {
				$this->add( 'appeal', $appeal->type, 'completed', $appeal->reviewed, (string)$appeal->outcome );
			}
		}

		foreach ( DPA::query()->toBase()->get( [ 'statutory', 'reject', 'filed', 'completed' ] ) as $dpa ) {
			$type = TransparencyReport::dpaType( $dpa->statutory );
			$this->add( 'dpa', $type, 'requested', $dpa->filed );
			if ( $dpa->completed !== null ) {
				$this->add( 'dpa', $type, 'completed', $dpa->completed, TransparencyReport::dpaOutcome( $dpa->reject ) );
			}
		}

		$events = UserEvent::query()->toBase()->whereIn( 'action', TransparencyReport::actionKeys() )->get( [ 'action', 'created' ] );
		foreach ( $events as $event ) {
			$this->add( 'action', $event->action, 'completed', $event->created );
		}

		$cells = $this->cells;
		$query = TransparencyReport::query();
		if ( !$includePublished ) {
			$locked = TransparencyReport::query()->distinct()->pluck( 'period' )
				->filter( static fn ( string $period ): bool => TransparencyStats::published( $period ) )
				->all();
			$cells = array_filter( $cells, static fn ( array $cell ): bool => !in_array( $cell['period'], $locked, true ) );
			$query->whereNotIn( 'period', $locked );
		}

		$query->delete();
		foreach ( array_chunk( array_values( $cells ), 200 ) as $chunk ) {
			TransparencyReport::query()->insert( $chunk );
		}

		return count( $cells );
	}

	/**
	 * Adds one to a counter in the month of the given date.
	 */
	private function add( string $category, string $type, string $column, mixed $date, string $outcome = '' ): void
	{
		$period = CarbonImmutable::parse( $date )->format( 'Y-m' );
		$key = $period . '|' . $category . '|' . $type . '|' . $outcome;
		$this->cells[$key] ??= [
			'period' => $period,
			'category' => $category,
			'type' => $type,
			'outcome' => $outcome,
			'requested' => 0,
			'completed' => 0,
		];
		$this->cells[$key][$column]++;
	}
}
