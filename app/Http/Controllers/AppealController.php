<?php

declare( strict_types = 1 );

namespace App\Http\Controllers;

use App\Models\Appeal;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Controller class for all Appeal actions.
 */
class AppealController
{
	/**
	 * Indexes all appeals, with filters for non-privileged users.
	 */
	public function index( Request $request ): View
	{
		$allAppeals = Appeal::query();
		$query = $request->query();

		foreach ( $query as $type => $key ) {
			if ( !$key ) {
				continue;
			} elseif ( $type === 'assigned' ) {
				$allAppeals = $allAppeals->where( $type, User::findById( (int)$key ) );
			} elseif ( in_array( $type, [ 'type', 'outcome' ], true ) ) {
				if ( $key === 'unknown' ) {
					$key = null;
				}

				$allAppeals = $allAppeals->where( $type, $key );
			}
		}

		if ( $request->input( 'closed' ) ) {
			$allAppeals = $allAppeals->whereNotNull( 'reviewed' );
		} else {
			$allAppeals = $allAppeals->whereNull( 'reviewed' );
		}

		return view( 'appeals' )->with( 'appeals', $allAppeals->get() );
	}

	/**
	 * Shows a specific appeal.
	 */
	public function show( Appeal $appeal ): View
	{
		return view( 'appeal.view' )->with( 'appeal', $appeal );
	}

	/**
	 * Processor for processing updates to an appeal.
	 */
	public function update( Appeal $appeal, Request $request ): RedirectResponse
	{
		$allInputs = $request->input();
		unset( $allInputs['_token'], $allInputs['_method'] );
		$appeal->update(
			[
				'review' => json_encode( $allInputs ),
				'assigned' => $request->user()->id,
				'outcome' => $allInputs['appeal-outcome'],
				'reviewed' => now(),
			]
		);

		$request->session()->flash( 'successFlash', __( 'appeal' ) . ' ' . __( 'toast-updated' ) );
		return redirect( "/appeal/{$appeal->id}" );
	}
}
