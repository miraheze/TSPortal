<?php

namespace App\Rules;

use App\Models\DPA;
use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;

class DPAAlreadyLive implements ValidationRule
{
	use ValidationTrait;

	/**
	 * Compare DPA user to see if another request already exists.
	 */
	public function passes( string $attribute, string $value ): bool
	{
		$userId = ( auth()->id() === User::findOrCreate( $value )->id ) ? auth()->id() : $value;
		return !( count( DPA::query()->where( 'user', $userId )->whereNull( 'completed' )->limit( 1 )->get() ) );
	}

	/**
	 * Get the validation error message.
	 */
	public function message(): string
	{
		return __( 'dpa-already-exists' );
	}
}
