<?php

namespace App\Rules;

use Closure;

trait ValidationTrait
{
	public function validate( string $attribute, mixed $value, Closure $fail ): void
	{
		if ( !$this->passes( $attribute, $value ) ) {
			$fail( $this->message() );
		}
	}
}
