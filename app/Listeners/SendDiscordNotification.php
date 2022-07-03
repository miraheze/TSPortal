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
			$fullModelName = explode( '\\', get_class( $event->model ) );
			$modelName = array_pop( $fullModelName );

			Http::post( config( 'app.discordhook' ), [
				'content' => 'New action for ' . $modelName . ' has been created. Link: ' . config( 'app.url' ) . '/' . strtolower( $modelName ) . '/' . $event->model->id
			] );
		}
	}
}
