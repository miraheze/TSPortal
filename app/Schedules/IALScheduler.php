<?php

namespace App\Schedules;

use App\Models\IAL;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;

class IALScheduler
{
	/**
	 * Invoke the scheduler task
	 *
	 * @return void
	 */
	public function __invoke()
	{
		$recentIALs = $this->getRecentIALs();

		if ( $recentIALs['total'] == 0 ) {
			return;
		}

		$message = $this->createMessage( $recentIALs );
		$this->notify( $message );
	}

	/**
	 * Gets all recent IALs and counts by types, actors and associations
	 *
	 * @return array
	 */
	private function getRecentIALs(): array
	{
		$data = [
			'total'        => 0,
			'actors'       => [],
			'types'        => [],
			'associations' => [
				'dpa'           => 0,
				'investigation' => 0,
				'unknown'       => 0
			]
		];

		$allRecentIALs = IAL::all()->where( 'added', '>=', Carbon::yesterday() );

		foreach ( $allRecentIALs as $ial ) {
			$data['total'] += 1;
			$data['actors'][] = $ial->user->username;
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
	 * Creates a webhook message based on recent IALs
	 *
	 * @param array $recentIALs
	 *
	 * @return array|string|string[]
	 */
	private function createMessage( array $recentIALs )
	{
		$varActors = '';
		foreach ( $recentIALs['actors'] as $actor => $num ) {
			$varActors .= $actor . '(' . $num . ') ';
		}

		$varAssociations = '';
		foreach ( array_count_values( $recentIALs['associations'] ) as $association => $num ) {
			$varAssociations .= $association . '(' . $num . ') ';
		}

		$varActions = '';
		foreach ( array_count_values( $recentIALs['types'] ) as $actions => $num ) {
			$varActions .= $actions . '(' . $num . ') ';
		}

		$replacements = [
			'{num:actions}'       => $recentIALs['total'],
			'{list:actors}'       => $varActors,
			'{list:associations}' => $varAssociations,
			'{list:actions}'      => $varActions
		];

		$msg = "**Trust and Safety Internal Action Log Daily Disgest!**\nOver the past 24 hours there have been **{num:actions}** actions!\n" .
			"The following members have taken actions today: {list:actors}\nThese actions have been associated to: {list:associations}\n" .
			"Finally, the actions completed are as follows: {list:actions}";

		return str_replace( array_keys( $replacements ), array_values( $replacements ), $msg );
	}

	/**
	 * Handle the notification.
	 *
	 * @param string $message
	 *
	 * @return void
	 */
	public function notify( string $message )
	{
		if ( config( 'app.discordhook' ) ) {
			if ( config( 'app.proxy' ) ) {
				Http::withOptions( [ 'proxy' => config( 'app.proxy' ) ] )->post( config( 'app.discordhook' ), [
					'content' => $message
				] );
			} else {
				Http::post( config( 'app.discordhook' ), [
					'content' => $message
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
