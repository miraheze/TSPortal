<?php

declare( strict_types = 1 );

namespace App\Observers;

use App\Models\Appeal;
use App\Models\DPA;
use App\Models\Investigation;
use App\Models\Report;
use App\Models\TransparencyReport;
use App\Models\UserEvent;
use Illuminate\Database\Eloquent\Model;
use function in_array;

/**
 * Keeps the transparency counters in step with the records they describe.
 */
class TransparencyObserver
{
	/**
	 * Handle a newly created record.
	 */
	public function created( Model $model ): void
	{
		$data = $model->getAttributes();
		match ( true ) {
			$model instanceof Report => $this->requested( 'report', $data['type'] ?? 'other' ),
			$model instanceof Investigation => $this->investigationCreated( $data ),
			$model instanceof Appeal => $this->requested( 'appeal', $data['type'] ?? 'new-information' ),
			$model instanceof DPA => $this->requested( 'dpa', TransparencyReport::dpaType( $data['statutory'] ?? false ) ),
			$model instanceof UserEvent && in_array( $data['action'] ?? '', TransparencyReport::actionKeys(), true )
				=> TransparencyReport::bump( 'action', $data['action'], TransparencyReport::COMPLETED ),
			default => null,
		};
	}

	/**
	 * Handle an updated record.
	 */
	public function updated( Model $model ): void
	{
		$data = $model->getAttributes();
		match ( true ) {
			$model instanceof Report => $this->toggled(
				$model,
				'reviewed',
				'report',
				$data['type'] ?? 'other',
				TransparencyReport::reportOutcome( $data['investigation'] ?? null, $data['dpa'] ?? false )
			),
			$model instanceof Investigation => $this->investigationUpdated( $model ),
			$model instanceof Appeal => $this->toggled( $model, 'reviewed', 'appeal', $data['type'] ?? 'new-information', $data['outcome'] ?? '' ),
			$model instanceof DPA => $this->toggled(
				$model,
				'completed',
				'dpa',
				TransparencyReport::dpaType( $data['statutory'] ?? false ),
				TransparencyReport::dpaOutcome( $data['reject'] ?? null )
			),
			default => null,
		};
	}

	/**
	 * Adds a request to the current month.
	 */
	private function requested( string $category, string $type ): void
	{
		TransparencyReport::bump( $category, $type, TransparencyReport::REQUESTED );
	}

	/**
	 * Counts a new investigation and any recommendation it starts with.
	 *
	 * @param array<string, mixed> $data
	 */
	private function investigationCreated( array $data ): void
	{
		$this->requested( 'investigation', $data['type'] ?? 'other' );
		$this->recommended( $data['recommendation'] ?? null );
	}

	/**
	 * Counts a closure, and a recommendation the first time one is made.
	 */
	private function investigationUpdated( Model $model ): void
	{
		$data = $model->getAttributes();
		$this->toggled( $model, 'closed', 'investigation', $data['type'] ?? 'other' );
		if ( $model->wasChanged( 'recommendation' ) && in_array( $model->getOriginal( 'recommendation' ), [ null, '', 'unknown' ], true ) ) {
			$this->recommended( $data['recommendation'] ?? null );
		}
	}

	/**
	 * Counts a completion, or takes it back when the record is reopened.
	 */
	private function toggled( Model $model, string $column, string $category, string $type, string $outcome = '' ): void
	{
		if ( !$model->wasChanged( $column ) ) {
			return;
		}

		$was = $model->getOriginal( $column );
		$now = $model->getAttributes()[$column] ?? null;
		match ( true ) {
			$was === null && $now !== null => TransparencyReport::bump( $category, $type, TransparencyReport::COMPLETED, $outcome ),
			$was !== null && $now === null => TransparencyReport::bump( $category, $type, TransparencyReport::COMPLETED, $outcome, -1 ),
			default => null,
		};
	}

	/**
	 * Counts a requested action once a real recommendation is made.
	 */
	private function recommended( ?string $recommendation ): void
	{
		if ( in_array( $recommendation, TransparencyReport::recommendationKeys(), true ) ) {
			TransparencyReport::bump( 'recommendation', $recommendation, TransparencyReport::REQUESTED );
		}
	}
}
