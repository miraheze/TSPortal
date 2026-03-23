<?php

declare( strict_types = 1 );

namespace App\Http\Controllers;

use App\Models\DPA;
use App\Models\IAL;
use App\Models\Investigation;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Controller class for Internal Actions Log requests and actions.
 */
class IALController
{
	/**
	 * Indexes and shows all IALs.
	 */
	public function index( Request $request ): View
	{
		$allIALs = IAL::latest( 'id' )->cursorPaginate( 25 );
		return view( 'ial' )->with( 'ials', $allIALs );
	}

	/**
	 * Processor for updating a request once processed.
	 */
	public function update( IAL $ial, Request $request ): RedirectResponse
	{
		$id = $request->input( 'assign-id' );
		if ( is_numeric( $id ) && Investigation::find( $id ) ) {
			$ial->update( [ 'investigation' => $id ] );
		} elseif ( ctype_alnum( $id ) && DPA::find( $id ) ) {
			$ial->update( [ 'dpa' => $id ] );
		} else {
			$request->session()->flash( 'failureFlash', __( 'ial' ) . ' ' . __( 'toast-invalid-id' ) );
			return back();
		}

		$request->session()->flash( 'successFlash', __( 'ial' ) . ' ' . __( 'toast-updated' ) );
		return back();
	}
}
