<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Http;

class SendWebhookNotification
{
	/**
	 * Create the event listener.
	 *
	 * @return void
	 */
	public function __construct()
	{
		//
	}

	/**
	 * Handle the event.
	 *
	 * @param $event
	 *
	 * @return void
	 */
	public function handle( $event )
	{
		if ( config( 'app.slackhook' ) ) {
			$text = 'New ' . $event->name . ' has been ' . $event->state . '. Link: ' . config( 'app.url' ) . '/' . strtolower( $event->name ) . '/' . $event->model->id;

			if ( config( 'app.proxy' ) ) {
				Http::withOptions( [ 'proxy' => config( 'app.proxy' ) ] )->post( config( 'app.slackhook' ), [
					'text' => $text,
					'username' => 'TSPortal',
				] );
			} else {
				Http::post( config( 'app.slackhook' ), [
					'text' => $text,
					'username' => 'TSPortal',
				] );
			}
		}
	}
}
