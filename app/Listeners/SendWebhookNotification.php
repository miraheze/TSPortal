<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Http;

class SendWebhookNotification
{
	/**
	 * Create the event listener.
	 */
	public function __construct()
	{
		//
	}

	/**
	 * Handle the event.
	 */
	public function handle( object $event ): void
	{
		$text = '';
		if ( !in_array( $event->state, [ 'closed', 'reopened' ], true ) ) {
			$text .= 'New ';
		}

		$text .= $event->name . ' has been ' . $event->state . '. Link: ' . config( 'app.url' ) . '/' . strtolower( $event->name ) . '/' . $event->model->id;

		if ( config( 'app.discordhook' ) ) {
			if ( config( 'app.proxy' ) ) {
				Http::withOptions( [ 'proxy' => config( 'app.proxy' ) ] )->post( config( 'app.discordhook' ), [
					'content' => $text,
				] );
			} else {
				Http::post( config( 'app.discordhook' ), [
					'content' => $text,
				] );
			}
		}

		if ( config( 'app.mattermosthook' ) ) {
			Http::post( config( 'app.mattermosthook' ), [
				'text' => $text,
				'username' => 'TSPortal',
			] );
		}
	}
}
