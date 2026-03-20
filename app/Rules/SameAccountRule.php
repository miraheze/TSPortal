<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\ValidationRule;

class SameAccountRule implements ValidationRule
{
	use ValidationTrait;

	/**
	 * Compare request user matches requested username.
	 */
	public function passes( string $attribute, string $value ): bool
	{
		return auth()->user()->username === $value;
	}

	/**
	 * Get the validation error message.
	 */
	public function message(): string
	{
		return __( 'username-not-same' );
	}
}
