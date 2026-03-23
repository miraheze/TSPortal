<?php

declare( strict_types = 1 );

namespace App\Schedules;

use App\Models\IAL;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;

class IALScheduler
{
	/**
	 * Invoke the scheduler task.
	 */
	public function __invoke(): void
	{
		$recentIALs = $this->getRecentIALs();
		if ( $recentIALs['total'] === 0 ) {
			return;
		}

		$message = $this->createMessage( $recentIALs );
		$this->notify( $message );
	}

	/**
	 * Gets recent IALs and counts by types, actors and associations.
	 */
	private function getRecentIALs(): array
	{
		$data = [
			'total' => 0,
			'actors' => [],
			'types' => [],
			'associations' => [
				'dpa' => 0,
				'investigation' => 0,
				'unknown' => 0,
			],
		];

		$recentIALs = IAL::where( 'added', '>=', Carbon::yesterday() )->get();
		foreach ( $recentIALs as $ial ) {
			$data['total'] += 1;

			$actor = $ial->user->username;
			$data['actors'][$actor] = ( $data['actors'][$actor] ?? 0 ) + 1;

			$data['types'][] = $ial->type;
			if ( $ial->dpa ) {
				$data['associations']['dpa'] += 1;
			} elseif ( $ial->investigation ) {
				$data['associations']['investigation'] += 1;
			} else {
				$data['associations']['unknown'] += 1;
			}
		}

		return $data;
	}

	/**
	 * Creates a webhook message based on recent IALs.
	 */
	private function createMessage( array $recentIALs ): string
	{
		$varActors = '';
		foreach ( $recentIALs['actors'] as $actor => $num ) {
			$varActors .= $actor . '(' . $num . ') ';
		}

		$varAssociations = '';
		foreach ( $recentIALs['associations'] as $association => $num ) {
			if ( $num === 0 ) {
				continue;
			}

			$varAssociations .= $association . '(' . $num . ') ';
		}

		$varActions = '';
		foreach ( array_count_values( $recentIALs['types'] ) as $action => $num ) {
			$varActions .= $action . '(' . $num . ') ';
		}

		$replacements = [
			'{num:actions}' => $recentIALs['total'],
			'{list:actors}' => $varActors,
			'{list:associations}' => $varAssociations,
			'{list:actions}' => $varActions,
		];

		$msg = "**Trust and Safety Internal Action Log Daily Disgest!**\nOver the past 24 hours there have been **{num:actions}** actions!\n" .
			"The following members have taken actions today: {list:actors}\nThese actions have been associated to: {list:associations}\n" .
			"Finally, the actions completed are as follows: {list:actions}";

		return str_replace( array_keys( $replacements ), array_values( $replacements ), $msg );
	}

	/**
	 * Handle the notification.
	 */
	public function notify( string $message ): void
	{
		if ( config( 'app.discordhook' ) ) {
			if ( config( 'app.proxy' ) ) {
				Http::withOptions( [ 'proxy' => config( 'app.proxy' ) ] )->post( config( 'app.discordhook' ), [
					'content' => $message,
				] );
			} else {
				Http::post( config( 'app.discordhook' ), [
					'content' => $message,
				] );
			}
		}

		if ( config( 'app.mattermosthook' ) ) {
			Http::post( config( 'app.mattermosthook' ), [
				'text' => $message,
				'username' => 'TSPortal',
			] );
		}
	}
}
