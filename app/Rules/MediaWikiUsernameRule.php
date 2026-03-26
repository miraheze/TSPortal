<?php

declare( strict_types = 1 );

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;
use Override;
use function config;
use function htmlspecialchars;

class MediaWikiUsernameRule implements ValidationRule
{
	/**
	 * Validate that the username is a valid user in MediaWiki.
	 */
	#[Override]
	public function validate( string $attribute, mixed $value, Closure $fail ): void
	{
		if ( !$value ) {
			$fail( 'username-exist' )->translate();
			return;
		}

		$response = Http::get( config( 'app.urls.mediawiki.api' ), [
			'format' => 'json',
			'action' => 'query',
			'meta' => 'globaluserinfo',
			'guiuser' => htmlspecialchars( $value ),
		] );

		if ( !isset( $response['query']['globaluserinfo']['id'] ) ) {
			$fail( 'username-exist' )->translate();
		}
	}
}
