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
		if ( $model instanceof Report ) {
			$this->requested( 'report', $data['type'] ?? 'other' );
		} elseif ( $model instanceof Investigation ) {
			$this->requested( 'investigation', $data['type'] ?? 'other' );
			$this->recommended( $data['recommendation'] ?? null );
		} elseif ( $model instanceof Appeal ) {
			$this->requested( 'appeal', $data['type'] ?? 'new-information' );
		} elseif ( $model instanceof DPA ) {
			$this->requested( 'dpa', $this->dpaType( $model ) );
		} elseif ( $model instanceof UserEvent && in_array( $data['action'] ?? '', TransparencyReport::actionKeys(), true ) ) {
			TransparencyReport::bump( 'action', $data['action'], TransparencyReport::COMPLETED );
		}
	}

	/**
	 * Handle an updated record.
	 */
	public function updated( Model $model ): void
	{
		$data = $model->getAttributes();
		if ( $model instanceof Report ) {
			$outcome = ( $data['investigation'] ?? null ) !== null ? 'investigation' : ( ( $data['dpa'] ?? false ) ? 'dpa' : 'closed' );
			$this->toggled( $model, 'reviewed', 'report', $data['type'] ?? 'other', $outcome );
		} elseif ( $model instanceof Investigation ) {
			$this->toggled( $model, 'closed', 'investigation', $data['type'] ?? 'other' );
			if ( $model->wasChanged( 'recommendation' ) && in_array( $model->getOriginal( 'recommendation' ), [ null, '', 'unknown' ], true ) ) {
				$this->recommended( $data['recommendation'] ?? null );
			}
		} elseif ( $model instanceof Appeal ) {
			$this->toggled( $model, 'reviewed', 'appeal', $data['type'] ?? 'new-information', $data['outcome'] ?? '' );
		} elseif ( $model instanceof DPA ) {
			$this->toggled( $model, 'completed', 'dpa', $this->dpaType( $model ), ( $data['reject'] ?? null ) === null ? 'accepted' : 'rejected' );
		}
	}

	/**
	 * Adds a request to the current month.
	 */
	private function requested( string $category, string $type ): void
	{
		TransparencyReport::bump( $category, $type, TransparencyReport::REQUESTED );
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
		if ( $was === null && $now !== null ) {
			TransparencyReport::bump( $category, $type, TransparencyReport::COMPLETED, $outcome );
		} elseif ( $was !== null && $now === null ) {
			TransparencyReport::bump( $category, $type, TransparencyReport::COMPLETED, $outcome, -1 );
		}
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

	/**
	 * Splits data protection requests by whether they are statutory.
	 */
	private function dpaType( Model $model ): string
	{
		return ( $model->getAttributes()['statutory'] ?? false ) ? 'statutory' : 'general';
	}
}
