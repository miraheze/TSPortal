<?php

declare( strict_types = 1 );

namespace App\Support;

use App\Models\TransparencyReport;
use Carbon\CarbonImmutable;
use function array_keys;
use function array_map;
use function array_sum;
use function config;
use function max;
use function min;
use function now;
use function round;
use function sprintf;
use function substr;

/**
 * Reads the monthly counters and shapes them for the transparency report.
 *
 * Counts under the threshold are hidden, and a second value is hidden when
 * one gap would otherwise be recoverable by subtraction.
 */
class TransparencyStats
{
	public const int THRESHOLD = 5;

	public const int DELAY_DAYS = 7;

	/**
	 * @var array<string, array<string, array<string, array{requested: int, completed: int}>>>
	 */
	private array $cells = [];

	/**
	 * @var array<string, array<string, int>>
	 */
	private array $perMonth = [];

	/**
	 * Loads the counters for the given months, written as Y-m.
	 *
	 * @param string[] $months
	 */
	public function __construct( private readonly array $months )
	{
		$rows = TransparencyReport::query()->toBase()->whereIn( 'period', $months )->get();
		foreach ( $rows as $row ) {
			$cell = &$this->cells[$row->category][$row->type][$row->outcome];
			$cell ??= [ 'requested' => 0, 'completed' => 0 ];
			$cell['requested'] += $row->requested;
			$cell['completed'] += $row->completed;
			unset( $cell );

			$this->perMonth[$row->period][$row->category] = ( $this->perMonth[$row->period][$row->category] ?? 0 ) + max( 0, $row->requested );
		}
	}

	/**
	 * Lists the periods that can be viewed, newest first.
	 *
	 * Keys are year, year-1 or year-2, and a period only appears once it has
	 * been over for the publishing delay.
	 *
	 * @return array<string, array{year: int, half: int, months: string[]}>
	 */
	public static function choices(): array
	{
		$first = TransparencyReport::query()->min( 'period' );
		if ( $first === null ) {
			return [];
		}

		$choices = [];
		for ( $year = now()->year; $year >= (int)substr( $first, 0, 4 ); $year-- ) {
			foreach ( [ 0, 2, 1 ] as $half ) {
				$last = $half === 1 ? 6 : 12;
				$end = CarbonImmutable::create( $year, $last, 1 )->endOfMonth();
				if ( $end->addDays( self::DELAY_DAYS )->isFuture() ) {
					continue;
				}

				$months = [];
				for ( $month = $half === 2 ? 7 : 1; $month <= $last; $month++ ) {
					$months[] = sprintf( '%04d-%02d', $year, $month );
				}

				$choices[$half === 0 ? (string)$year : $year . '-' . $half] = [ 'year' => $year, 'half' => $half, 'months' => $months ];
			}
		}

		return $choices;
	}

	/**
	 * Tells whether a month belongs to a period that is already published.
	 */
	public static function published( string $month ): bool
	{
		$date = CarbonImmutable::createFromFormat( '!Y-m', $month );
		$end = $date->month <= 6 ? $date->setMonth( 6 ) : $date->setMonth( 12 );

		return !$end->endOfMonth()->addDays( self::DELAY_DAYS )->isFuture();
	}

	/**
	 * Hides a count that is above zero but under the threshold.
	 */
	public static function mask( int $count ): ?int
	{
		return $count > 0 && $count < self::THRESHOLD ? null : $count;
	}

	/**
	 * Hides small counts in a group and blocks recovering them by subtraction.
	 *
	 * @param array<int|string, int> $values
	 * @return array<int|string, int|null>
	 */
	public static function suppress( array $values ): array
	{
		$out = array_map( self::mask( ... ), $values );
		$hidden = 0;
		foreach ( $out as $value ) {
			if ( $value === null ) {
				$hidden++;
			}
		}

		if ( $hidden === 1 ) {
			$smallest = null;
			foreach ( $out as $key => $value ) {
				if ( $value !== null && $value > 0 && ( $smallest === null || $value < $out[$smallest] ) ) {
					$smallest = $key;
				}
			}

			if ( $smallest !== null ) {
				$out[$smallest] = null;
			}
		}

		return $out;
	}

	/**
	 * Returns every section of the report in one array.
	 *
	 * @return array<string, mixed>
	 */
	public function all(): array
	{
		return [
			'reports' => $this->reports(),
			'investigations' => $this->investigations(),
			'actions' => $this->actions(),
			'appeals' => $this->appeals(),
			'dpa' => $this->dpa(),
			'monthly' => $this->monthly(),
		];
	}

	/**
	 * Figures for reports received in the period.
	 *
	 * @return array<string, mixed>
	 */
	public function reports(): array
	{
		$types = [];
		foreach ( config( 'app.reportTopics' ) as $group => $topics ) {
			foreach ( $topics as $topic ) {
				$types[] = $group . '-' . $topic;
			}
		}

		return [
			'requested' => self::mask( $this->value( 'report', 'requested' ) ),
			'completed' => self::mask( $this->value( 'report', 'completed' ) ),
			'outcomes' => self::suppress( [
				'investigation' => $this->value( 'report', 'completed', null, 'investigation' ),
				'dpa' => $this->value( 'report', 'completed', null, 'dpa' ),
				'closed' => $this->value( 'report', 'completed', null, 'closed' ),
			] ),
			'types' => $this->rows( 'report', $types, [ 'requested', 'completed' ] ),
		];
	}

	/**
	 * Figures for investigations opened or closed in the period.
	 *
	 * @return array<string, mixed>
	 */
	public function investigations(): array
	{
		return [
			'requested' => self::mask( $this->value( 'investigation', 'requested' ) ),
			'completed' => self::mask( $this->value( 'investigation', 'completed' ) ),
			'topics' => $this->rows( 'investigation', config( 'app.investigationTopics' ), [ 'requested', 'completed' ] ),
			'recommendations' => $this->rows( 'recommendation', TransparencyReport::recommendationKeys(), [ 'requested' ] ),
		];
	}

	/**
	 * Counts of the actions recorded against accounts, grouped by kind.
	 *
	 * @return array<string, array<int, array<string, mixed>>>
	 */
	public function actions(): array
	{
		$grouped = [];
		foreach ( config( 'app.events' ) as $group => $events ) {
			if ( $group === 'appeal' ) {
				continue;
			}

			$keys = [];
			foreach ( $events as $event ) {
				$keys[] = $group . '-' . $event;
			}

			$grouped[$group] = $this->rows( 'action', $keys, [ 'completed' ] );
		}

		return $grouped;
	}

	/**
	 * Figures for appeals received in the period.
	 *
	 * @return array<string, mixed>
	 */
	public function appeals(): array
	{
		$types = array_keys( config( 'app.appeals' ) );
		$received = $upheld = $notUpheld = [];
		foreach ( $types as $type ) {
			$received[$type] = $this->value( 'appeal', 'requested', $type );
			$upheld[$type] = $this->value( 'appeal', 'completed', $type, 'upheld' );
			$notUpheld[$type] = $this->value( 'appeal', 'completed', $type, 'not-upheld' );
		}

		$received = self::suppress( $received );
		$upheld = self::suppress( $upheld );
		$notUpheld = self::suppress( $notUpheld );
		$rows = [];
		foreach ( $types as $type ) {
			$rows[$type] = [ 'requested' => $received[$type], 'upheld' => $upheld[$type], 'not-upheld' => $notUpheld[$type] ];
		}

		$outcomes = self::suppress( [
			'upheld' => $this->value( 'appeal', 'completed', null, 'upheld' ),
			'not-upheld' => $this->value( 'appeal', 'completed', null, 'not-upheld' ),
		] );

		return [
			'requested' => self::mask( $this->value( 'appeal', 'requested' ) ),
			'completed' => self::mask( $this->value( 'appeal', 'completed' ) ),
			'upheld' => $outcomes['upheld'],
			'not-upheld' => $outcomes['not-upheld'],
			'types' => $rows,
		];
	}

	/**
	 * Figures for data protection requests filed in the period.
	 *
	 * @return array<string, mixed>
	 */
	public function dpa(): array
	{
		$outcomes = self::suppress( [
			'accepted' => $this->value( 'dpa', 'completed', null, 'accepted' ),
			'rejected' => $this->value( 'dpa', 'completed', null, 'rejected' ),
		] );

		return [
			'requested' => self::mask( $this->value( 'dpa', 'requested' ) ),
			'completed' => self::mask( $this->value( 'dpa', 'completed' ) ),
			'statutory' => self::mask( $this->value( 'dpa', 'requested', 'statutory' ) ),
			'accepted' => $outcomes['accepted'],
			'rejected' => $outcomes['rejected'],
		];
	}

	/**
	 * Requests received in each month of the period.
	 *
	 * @return array<string, array<string, int|null>>
	 */
	public function monthly(): array
	{
		$names = [ 'reports' => 'report', 'investigations' => 'investigation', 'appeals' => 'appeal', 'dpa' => 'dpa' ];
		$columns = [];
		foreach ( $names as $name => $category ) {
			$raw = [];
			foreach ( $this->months as $month ) {
				$raw[$month] = $this->perMonth[$month][$category] ?? 0;
			}

			$columns[$name] = self::suppress( $raw );
		}

		$rows = [];
		foreach ( $this->months as $month ) {
			$rows[$month] = [];
			foreach ( $names as $name => $category ) {
				$rows[$month][$name] = $columns[$name][$month];
			}
		}

		return $rows;
	}

	/**
	 * Builds rows for each key with hidden small counts and a share of the first column.
	 *
	 * @param string[] $keys
	 * @param string[] $columns
	 * @return array<int, array<string, mixed>>
	 */
	private function rows( string $category, array $keys, array $columns ): array
	{
		$raw = [];
		foreach ( $columns as $column ) {
			foreach ( $keys as $key ) {
				$raw[$column][$key] = $this->value( $category, $column, $key );
			}
		}

		$shown = array_map( self::suppress( ... ), $raw );
		$total = array_sum( $raw[$columns[0]] );
		$rows = [];
		foreach ( $keys as $key ) {
			$row = [
				'key' => $key,
				'percent' => $shown[$columns[0]][$key] !== null && $total > 0
					? min( 100, (int)round( $raw[$columns[0]][$key] / $total * 100 ) )
					: ( $shown[$columns[0]][$key] === null ? null : 0 ),
			];
			foreach ( $columns as $column ) {
				$row[$column] = $shown[$column][$key];
			}

			$rows[] = $row;
		}

		return $rows;
	}

	/**
	 * Adds up one counter, optionally narrowed to a type or an outcome.
	 */
	private function value( string $category, string $column, ?string $type = null, ?string $outcome = null ): int
	{
		$sum = 0;
		foreach ( $this->cells[$category] ?? [] as $cellType => $outcomes ) {
			if ( $type !== null && (string)$cellType !== $type ) {
				continue;
			}

			foreach ( $outcomes as $cellOutcome => $values ) {
				if ( $outcome === null || (string)$cellOutcome === $outcome ) {
					$sum += max( 0, $values[$column] );
				}
			}
		}

		return $sum;
	}
}
