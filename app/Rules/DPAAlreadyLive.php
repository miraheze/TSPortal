<?php

declare( strict_types = 1 );

namespace App\Rules;

use App\Models\DPA;
use App\Models\User;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Override;

class DPAAlreadyLive implements ValidationRule
{
	/**
	 * Validate that there is not already another DPA request for this user.
	 */
	#[Override]
	public function validate( string $attribute, mixed $value, Closure $fail ): void
	{
		if ( !$value ) {
			return;
		}

		$userId = User::firstWhere( 'username', $value )?->id;
		if ( $userId === null ) {
			return;
		}

		$exists = DPA::where( 'user', $userId )->whereNull( 'completed' )->exists();
		if ( $exists ) {
			$fail( 'dpa-already-exists' )->translate();
		}
	}
}
