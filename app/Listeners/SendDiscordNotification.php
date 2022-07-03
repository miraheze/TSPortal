<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Http;

class SendDiscordNotification
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
		if ( config( 'app.discordhook' ) ) {
			$content = 'New ' . $event->name . ' has been ' . $event->state . '. Link: ' . config( 'app.url' ) . '/' . strtolower( $event->name ) . '/' . $event->model->id;

			if ( config( 'app.proxy' ) ) {
				Http::withOptions( [ 'proxy' => config( 'app.proxy' ) ] )->post( config( 'app.discordhook' ), [
					'content' => $content
				] );
			} else {
				Http::post( config( 'app.discordhook' ), [
					'content' => $content
				] );
			}
		}
	}
}
