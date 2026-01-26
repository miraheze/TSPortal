<?php

namespace App\Rules;

use App\Models\DPA;
use App\Models\User;
use Illuminate\Contracts\Validation\Rule;

class DPAAlreadyLive implements Rule
{
	/**
	 * Create a new rule instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		//
	}

	/**
	 * Compare DPA user to see if another request already exists.
	 *
	 * @param  string  $attribute
	 * @param  mixed  $value
	 */
	public function passes( $attribute, $value ): bool
	{
		$userId = ( auth()->id() == User::findOrCreate( $value )->id ) ? auth()->id() : $value;

		return ! ( count( DPA::query()->where( 'user', $userId )->whereNull( 'completed' )->limit( 1 )->get() ) );
	}

	/**
	 * Get the validation error message.
	 */
	public function message(): string
	{
		return __( 'dpa-already-exists' );
	}
}
