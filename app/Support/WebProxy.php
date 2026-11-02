<?php

declare( strict_types = 1 );

namespace App\Support;

use function config;
use function is_string;

class WebProxy
{
	/**
	 * Guzzle request options for the configured proxy. Empty when none is set.
	 *
	 * @return array{proxy?: string}
	 */
	public static function options(): array
	{
		$proxy = config( 'app.proxy' );
		if ( !is_string( $proxy ) || $proxy === '' ) {
			return [];
		}

		return [ 'proxy' => $proxy ];
	}
}
