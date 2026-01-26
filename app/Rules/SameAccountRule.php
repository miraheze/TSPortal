<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class SameAccountRule implements Rule
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
     * Compare request user matches requested username.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     */
    public function passes($attribute, $value): bool
    {
        return auth()->user()->username == $value;
    }

    /**
     * Get the validation error message.
     */
    public function message(): string
    {
        return __('username-not-same');
    }
}
