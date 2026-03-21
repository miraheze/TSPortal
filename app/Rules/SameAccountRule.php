<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Override;

class SameAccountRule implements ValidationRule
{
	/**
	 * Validate that the request user matches the requested username.
	 */
	#[Override]
	public function validate( string $attribute, mixed $value, Closure $fail ): void
	{
		if ( auth()->user()->username !== $value ) {
			$fail( 'username-not-same' )->translate();
		}
	}
}
