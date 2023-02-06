<?php

use App\Events\DPANew;
use App\Events\ReportNew;
use App\Models\DPA;
use App\Models\IAL;
use App\Models\Investigation;
use App\Models\Report;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group.
|
*/

/*
 * DPA API Group
 */
Route::get( 'dpa/{dpa}/{username}', function( DPA $dpa, string $username ) {
	return response()->json( [
		'dpa-id'   => $dpa->id,
		'username' => $username,
		'match'    => ( $dpa->user->username == $username )
	] );
} );

Route::post( 'dpa', function( Request $request ) {
	if ( config( 'auth.writekey' ) != $request->input( 'writekey' ) ) {
		return response()->json( [ 'unauthorised' => true ] );
	}

	$dpaUser = User::findOrCreate( $request->input( 'username' ) );

	if ( count( DPA::query()->where( 'user', $dpaUser->id )->whereNull( 'completed' )->limit( 1 )->get() ) ) {
		return response()->json( [ 'exists' => true ] );
	}

	DPA::factory()->create(
		[
			'user'      => $dpaUser,
			'underage'  => $request->input( 'evidence' ),
			'statutory' => true
		]
	);

	$event = ( count( $dpaUser->events ) == 0 ) ? 'created-dpa' : 'new-dpa';

	$dpaUser->newEvent( $event );

	$newDPA = DPA::query()->orderBy( 'filed', 'DESC' )->limit( 1 )->get()->all()[0];

	DPANew::dispatch( $newDPA );

	return response()->json( [
		'id' => $newDPA->id
	] );
} );

/*
 * Reports API Group
 */
Route::post( 'report', function( Request $request ) {
	if ( config( 'auth.writekey' ) != $request->input( 'writekey' ) ) {
		return response()->json( [ 'unauthorised' => true ] );
	}

	$subjectUser = User::findOrCreate( $request->input( 'username' ) );
	$reportingUser = User::findOrCreate( $request->input( 'reporter' ) );

	$newReport = Report::factory()->create(
		[
			'type'     => $request->input( 'report' ),
			'user'     => $subjectUser,
			'reporter' => $reportingUser,
			'text'     => $request->input( 'evidence' ),
		]
	);

	$event = ( count( $subjectUser->events ) == 0 ) ? 'created-report' : 'new-report';

	$subjectUser->newEvent( $event, $newReport->id );

	$reportingUser->newEvent( 'filed-report', $newReport->id );

	ReportNew::dispatch( $newReport );

	return response()->json( [
		'id' => $newReport->id
	] );
} );

/*
 * Internal Actions Log
 */
Route::post( 'ial', function( Request $request ) {
	if ( config( 'auth.writekey' ) != $request->input( 'writekey' ) ) {
		return response()->json( [ 'unauthorised' => true ] );
	}

	$comment = $request->input( 'comment' );
	$explodedComment = explode( '#', $comment );

	$serialisedID = ( is_array( $explodedComment ) ) ? preg_replace( '/[^a-z\d]/i', '', $explodedComment[1] ) : null;

	$updates = [
		'user'          => User::findOrCreate( $request->input( 'username' ) )->id,
		'type'          => $request->input( 'log' ),
		'wiki'          => $request->input( 'wiki' ),
		'comments'      => $comment,
		'dpa'           => null,
		'investigation' => null
	];

	if ( is_numeric( $serialisedID ) && Investigation::all()->find( $serialisedID ) ) {
		$updates['investigation'] = $serialisedID;
	} elseif ( ctype_alnum( $serialisedID ) && DPA::all()->find( $serialisedID ) ) {
		$updates['dpa'] = $serialisedID;
	}

	$newIAL = IAL::factory()->create( $updates );

	return response()->json( [
		'id' => $newIAL->id
	] );
} );
