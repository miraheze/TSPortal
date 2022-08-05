<?php

use App\Models\DPA;
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

Route::get( 'dpa/{dpa}/{username}', function( DPA $dpa, string $username ) {
	return response()->json( [
		'dpa-id'   => $dpa->id,
		'username' => $username,
		'match'    => ( $dpa->user->username == $username )
	] );
} );
