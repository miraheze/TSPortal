<?php

namespace App\View\Components\User;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Verified extends Component
{
	/**
	 * User we want to verify.
	 */
	public User $user;

	/**
	 * Create a new component instance.
	 */
	public function __construct( User $user )
	{
		$this->user = $user;
	}

	/**
	 * Get the view that represent the component.
	 */
	public function render(): View
	{
		return view( 'components.user.verified' );
	}
}
