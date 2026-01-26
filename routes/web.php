<?php

use App\Http\Controllers\AppealController;
use App\Http\Controllers\DPAController;
use App\Http\Controllers\IALController;
use App\Http\Controllers\InvestigationController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use App\Models\Appeal;
use App\Models\DPA;
use App\Models\IAL;
use App\Models\Investigation;
use App\Models\Report;
use App\Models\User;
use Illuminate\Support\Facades\Route;

/*
 * Main web route returning the home/dashboard view.
 */
Route::get( '/', function () {
	return view( 'home' );
} );

/*
 * Web group for account actions
 */
Route::get( '/login', [ LoginController::class, 'login' ] )->name( 'login' );
Route::get( '/callback', [ LoginController::class, 'callback' ] );
Route::get( '/logout', [ LoginController::class, 'logout' ] )->middleware( 'auth' );
Route::get( '/user', [ UserController::class, 'index' ] )->middleware( 'auth' )->can( 'viewAny', User::class );
Route::get( '/user/{user}', [ UserController::class, 'show' ] )->whereNumber( 'id' )->middleware( 'auth' )->can( 'view', 'user' );
Route::patch( '/user/{user}', [ UserController::class, 'update' ] )->whereNumber( 'id' )->middleware( 'auth' )->can( 'update', 'user' );

/*
 * Investigations web group
 */
Route::get( '/investigations', [ InvestigationController::class, 'index' ] )->middleware( 'auth' )->can( 'viewAny', Investigation::class );
Route::get( '/investigation/new', [ InvestigationController::class, 'create' ] )->middleware( 'auth' )->can( 'create', Investigation::class );
Route::post( '/investigation/new', [ InvestigationController::class, 'store' ] )->middleware( 'auth' )->can( 'create', Investigation::class );
Route::get( '/investigation/{investigation}', [ InvestigationController::class, 'show' ] )->whereNumber( 'id' )->middleware( 'auth' )->can( 'view', 'investigation' );
Route::get( '/investigation/edit/{investigation}', [ InvestigationController::class, 'edit' ] )->whereNumber( 'id' )->middleware( 'auth' )->can( 'update', 'investigation' );
Route::patch( '/investigation/{investigation}', [ InvestigationController::class, 'update' ] )->whereNumber( 'id' )->middleware( 'auth' )->can( 'update', 'investigation' );

/*
 * Reports web group
 */
Route::get( '/reports', [ ReportController::class, 'index' ] )->middleware( 'auth' );
Route::get( '/report/new', [ ReportController::class, 'create' ] )->middleware( 'auth' )->can( 'create', Report::class );
Route::post( '/report/new', [ ReportController::class, 'store' ] )->middleware( 'auth' )->can( 'create', Report::class );
Route::get( '/report/{report}', [ ReportController::class, 'show' ] )->whereNumber( 'id' )->middleware( 'auth' )->can( 'view', 'report' );
Route::patch( '/report/{report}', [ ReportController::class, 'update' ] )->whereNumber( 'id' )->middleware( 'auth' )->can( 'update', 'report' );

/*
 * DPA web groups
 */
Route::get( '/dpa', [ DPAController::class, 'index' ] )->middleware( 'auth' );
Route::get( '/dpa/new', [ DPAController::class, 'create' ] )->middleware( 'auth' )->can( 'create', DPA::class );
Route::post( '/dpa/new', [ DPAController::class, 'store' ] )->middleware( 'auth' )->can( 'create', DPA::class );
Route::get( '/dpa/{dpa}', [ DPAController::class, 'show' ] )->whereAlphaNumeric( 'id' )->middleware( 'auth' )->can( 'view', 'dpa' );
Route::patch( '/dpa/{dpa}', [ DPAController::class, 'update' ] )->whereAlphaNumeric( 'id' )->middleware( 'auth' )->can( 'update', 'dpa' );
Route::redirect( '/gdpr', '/dpa' );

/*
 * Appeal web group
 */
Route::get( '/appeals', [ AppealController::class, 'index' ] )->middleware( 'auth' )->can( 'viewAny', Appeal::class );
Route::get( '/appeal/{appeal}', [ AppealController::class, 'show' ] )->whereNumber( 'id' )->middleware( 'auth' )->can( 'view', 'appeal' );
Route::patch( '/appeal/{appeal}', [ AppealController::class, 'update' ] )->whereNumber( 'id' )->middleware( 'auth' )->can( 'update', 'appeal' );

/*
 * Internal Actions Log web group
 */
Route::get( '/ial', [ IALController::class, 'index' ] )->middleware( 'auth' )->can( 'viewAny', IAL::class );
Route::patch( '/ial/{ial}', [ IALController::class, 'update' ] )->whereNumber( 'id' )->middleware( 'auth' )->can( 'update', 'ial' );
