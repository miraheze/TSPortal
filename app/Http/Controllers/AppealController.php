<?php

declare( strict_types = 1 );

namespace App\Http\Controllers;

use App\Models\Appeal;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use function in_array;
use function json_encode;
use function now;
use function redirect;
use function view;

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
		$query = Appeal::query();
		foreach ( $request->query() as $type => $key ) {
			if ( !$key ) {
				continue;
			}

			if ( in_array( $type, [ 'assigned', 'type', 'outcome' ], true ) ) {
				if ( $key === 'unknown' ) {
					$key = null;
				}

				$query->where( $type, $key );
			}
		}

		if ( $request->input( 'closed' ) ) {
			$query->whereNotNull( 'reviewed' );
		} else {
			$query->whereNull( 'reviewed' );
		}

		return view( 'appeals' )->with( 'appeals', $query->get() );
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
		$appeal->update( [
			'review' => json_encode( $allInputs ),
			'assigned' => $request->user()->id,
			'outcome' => $allInputs['appeal-outcome'],
			'reviewed' => now(),
		] );

		$request->session()->flash( 'successFlash', __( 'appeal' ) . ' ' . __( 'toast-updated' ) );
		return redirect( "/appeal/{$appeal->id}" );
	}
}
