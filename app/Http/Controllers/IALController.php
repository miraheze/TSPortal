<?php

namespace App\Http\Controllers;

use App\Models\DPA;
use App\Models\IAL;
use App\Models\Investigation;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Controller class for Internal Actions Log requests and actions
 */
class IALController extends Controller
{
	/**
	 * Indexes and shows all IALs
	 *
	 * @param Request $request
	 *
	 * @return Application|Factory|View
	 */
	public function index( Request $request )
	{
		$allIALs = DB::table( 'ial' )->orderBy( 'id', 'DESC' )->cursorPaginate( 25 );


		return view( 'ial' )->with( 'ials', $allIALs );
	}

	/**
	 * Processor for updating a request once processed
	 *
	 * @param IAL $ial
	 * @param Request $request
	 *
	 * @return RedirectResponse
	 */
	public function update( IAL $ial, Request $request ): RedirectResponse
	{
		$id = $request->input( 'assign-id' );

		if ( is_numeric( $id ) && Investigation::all()->find( $id ) ) {
			$ial->update(
				[
					'investigation' => $id
				]
			);
		} elseif ( ctype_alnum( $id ) && DPA::all()->find( $id ) ) {
			$ial->update(
				[
					'dpa' => $id
				]
			);
		} else {
			request()->session()->flash( 'failureFlash', __( 'ial' ) . ' ' . __( 'toast-invalid-id' ) );
			return back();
		}

		request()->session()->flash( 'successFlash', __( 'ial' ) . ' ' . __( 'toast-updated' ) );

		return back();
	}
}
