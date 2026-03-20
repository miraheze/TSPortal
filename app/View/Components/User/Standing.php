<?php

declare( strict_types = 1 );

namespace App\View\Components\User;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Standing extends Component
{
	/**
	 * User standing.
	 */
	public string $standing;

	/**
	 * Create a new component instance.
	 */
	public function __construct( User $user )
	{
		$this->standing = $user->getStanding();
	}

	/**
	 * Get the view that represent the component.
	 */
	public function render(): View
	{
		return view( 'components.user.standing' );
	}
}
