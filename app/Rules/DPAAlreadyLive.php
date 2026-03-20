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
		$userId = ( auth()->id() === User::findOrCreate( $value )->id ) ? auth()->id() : $value;
		$check = !( count( DPA::query()->where( 'user', $userId )->whereNull( 'completed' )->limit( 1 )->get() ) );
		if ( !$check ) {
			$fail( 'dpa-already-exists' )->translate();
		}
	}
}
