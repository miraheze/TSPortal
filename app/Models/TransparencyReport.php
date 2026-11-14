<?php

declare( strict_types = 1 );

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Unguarded;
use Illuminate\Database\Eloquent\Attributes\WithoutTimestamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use function array_diff;
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
	 * Event keys that count as a completed action against an account.
	 *
	 * @return string[]
	 */
	public static function actionKeys(): array
	{
		$keys = [];
		foreach ( config( 'app.events' ) as $group => $events ) {
			if ( $group === 'appeal' ) {
				continue;
			}

			foreach ( $events as $event ) {
				$keys[] = $group . '-' . $event;
			}
		}

		return $keys;
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
}
