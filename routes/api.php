<?php

declare( strict_types = 1 );

use App\Events\DPANew;
use App\Events\ReportNew;
use App\Models\DPA;
use App\Models\IAL;
use App\Models\Investigation;
use App\Models\Report;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

/*
 * DPA API Group.
 */
Route::get( 'dpa/{dpa}/{username}', static function ( DPA $dpa, string $username ): JsonResponse {
	return response()->json( [
		'dpa-id' => $dpa->id,
		'username' => $username,
		'match' => $dpa->user->username === $username,
	] );
} );

Route::post( 'dpa', static function ( Request $request ): JsonResponse {
	if ( config( 'app.writekey' ) !== $request->input( 'writekey' ) ) {
		return response()->json( [ 'unauthorized' => true ] );
	}

	$dpaUser = User::findOrCreate( $request->input( 'username' ) );
	if ( DPA::where( 'user', $dpaUser->id )->whereNull( 'completed' )->exists() ) {
		return response()->json( [ 'exists' => true ] );
	}

	DPA::factory()->create(
		[
			'user' => $dpaUser,
			'underage' => $request->input( 'evidence' ),
			'statutory' => true,
		]
	);

	$event = $dpaUser->events()->exists() ? 'new-dpa' : 'created-dpa';
	$dpaUser->newEvent( $event );

	$newDPA = DPA::latest( 'filed' )->first();
	DPANew::dispatch( $newDPA );

	return response()->json( [ 'id' => $newDPA->id ] );
} );

/*
 * Reports API Group.
 */
Route::post( 'report', static function ( Request $request ): JsonResponse {
	if ( config( 'app.writekey' ) !== $request->input( 'writekey' ) ) {
		return response()->json( [ 'unauthorized' => true ] );
	}

	$subjectUser = User::findOrCreate( $request->input( 'username' ) );
	$reportingUser = User::findOrCreate( $request->input( 'reporter' ) );

	$newReport = Report::factory()->create(
		[
			'type' => $request->input( 'report' ),
			'auto' => $request->boolean( 'auto' ),
			'user' => $subjectUser,
			'reporter' => $reportingUser,
			'text' => $request->input( 'evidence' ),
		]
	);

	$event = $subjectUser->events()->exists() ? 'new-report' : 'created-report';
	$subjectUser->newEvent( $event, (string)$newReport->id );

	$reportingUser->newEvent( 'filed-report', (string)$newReport->id );
	ReportNew::dispatch( $newReport );

	return response()->json( [ 'id' => $newReport->id ] );
} );

/*
 * Internal Actions Log API Group.
 */
Route::post( 'ial', static function ( Request $request ): JsonResponse {
	if ( config( 'app.writekey' ) !== $request->input( 'writekey' ) ) {
		return response()->json( [ 'unauthorized' => true ] );
	}

	$comment = $request->input( 'comment' ) ?? '';
	$explodedComment = explode( '#', $comment );

	$serializedID = isset( $explodedComment[1] ) ? preg_replace( '/[^a-z\d]/i', '', $explodedComment[1] ) : null;

	$updates = [
		'user' => User::findOrCreate( $request->input( 'username' ) ),
		'type' => $request->input( 'log' ),
		'wiki' => $request->input( 'wiki' ),
		'comments' => $comment,
		'dpa' => null,
		'investigation' => null,
	];

	if ( is_numeric( $serializedID ) && Investigation::find( $serializedID ) ) {
		$updates['investigation'] = $serializedID;
	} elseif ( ctype_alnum( $serializedID ) && DPA::find( $serializedID ) ) {
		$updates['dpa'] = $serializedID;
	}

	$newIAL = IAL::factory()->create( $updates );
	return response()->json( [ 'id' => $newIAL->id ] );
} );
