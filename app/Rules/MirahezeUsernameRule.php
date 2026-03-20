<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;

class MirahezeUsernameRule implements ValidationRule
{
	use ValidationTrait;

	/**
	 * Conduct a HTTP request.
	 */
	public function passes( string $attribute, string $value ): bool
	{
		return ( Http::get( 'https://login.miraheze.org/w/api.php?format=json&action=query&meta=globaluserinfo&guiuser=' . htmlspecialchars( $value ) )['query']['globaluserinfo']['id'] ?? false );
	}

	/**
	 * Get the validation error message.
	 */
	public function message(): string
	{
		return __( 'username-exist' );
	}
}
