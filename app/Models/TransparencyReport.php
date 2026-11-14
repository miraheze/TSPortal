<?php

declare( strict_types = 1 );

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Unguarded;
use Illuminate\Database\Eloquent\Attributes\WithoutTimestamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use function array_diff;
use function array_diff_key;
use function array_map;
use function array_merge;
use function array_values;
use function config;
use function now;

/**
 * Monthly counters behind the transparency report.
 *
 * Rows hold no user, report or investigation reference on purpose.
 */
#[Unguarded]
#[WithoutTimestamps]
class TransparencyReport extends Model
{
	public const string REQUESTED = 'requested';

	public const string COMPLETED = 'completed';

	public $incrementing = false;

	protected $primaryKey = null;

	/**
	 * Adds to one counter for the given month, creating the row if needed.
	 */
	public static function bump( string $category, string $type, string $column, string $outcome = '', int $by = 1, ?string $period = null ): void
	{
		self::query()->upsert(
			[ [
				'period' => $period ?? now()->format( 'Y-m' ),
				'category' => $category,
				'type' => $type,
				'outcome' => $outcome,
				'requested' => $column === self::REQUESTED ? $by : 0,
				'completed' => $column === self::COMPLETED ? $by : 0,
			] ],
			[ 'period', 'category', 'type', 'outcome' ],
			[ $column => DB::raw( $column . ' + ' . $by ) ]
		);
	}

	/**
	 * Account action keys grouped by kind, leaving out appeal events.
	 *
	 * @return array<string, string[]>
	 */
	public static function actionGroups(): array
	{
		$groups = [];
		foreach ( array_diff_key( config( 'app.events' ), [ 'appeal' => true ] ) as $group => $events ) {
			$groups[$group] = array_map( static fn ( string $event ): string => $group . '-' . $event, $events );
		}

		return $groups;
	}

	/**
	 * Event keys that count as a completed action against an account.
	 *
	 * @return string[]
	 */
	public static function actionKeys(): array
	{
		return array_merge( [], ...array_values( self::actionGroups() ) );
	}

	/**
	 * Every report topic written as group-topic.
	 *
	 * @return string[]
	 */
	public static function reportTypes(): array
	{
		$types = [];
		foreach ( config( 'app.reportTopics' ) as $group => $topics ) {
			foreach ( $topics as $topic ) {
				$types[] = $group . '-' . $topic;
			}
		}

		return $types;
	}

	/**
	 * Recommendations that can be requested, leaving out the unknown one.
	 *
	 * @return string[]
	 */
	public static function recommendationKeys(): array
	{
		return array_values( array_diff( config( 'app.recommendations' ), [ 'unknown' ] ) );
	}

	/**
	 * What became of a reviewed report.
	 */
	public static function reportOutcome( mixed $investigation, mixed $dpa ): string
	{
		return match ( true ) {
			$investigation !== null => 'investigation',
			(bool)$dpa => 'dpa',
			default => 'closed',
		};
	}

	/**
	 * Splits data protection requests by whether they are statutory.
	 */
	public static function dpaType( mixed $statutory ): string
	{
		return $statutory ? 'statutory' : 'general';
	}

	/**
	 * How a finished data protection request was decided.
	 */
	public static function dpaOutcome( mixed $reject ): string
	{
		return $reject === null ? 'accepted' : 'rejected';
	}
}
