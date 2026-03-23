<?php

declare( strict_types = 1 );

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;
use Override;

class MirahezeUsernameRule implements ValidationRule
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

		$valid = Http::get( 'https://login.miraheze.org/w/api.php?format=json&action=query&meta=globaluserinfo&guiuser=' . htmlspecialchars( $value ) )['query']['globaluserinfo']['id'] ?? false;
		if ( !$valid ) {
			$fail( 'username-exist' )->translate();
		}
	}
}
