<?php

declare( strict_types = 1 );

namespace App\View\Components\User;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Override;

class Verified extends Component
{
	/**
	 * Create a new component instance.
	 */
	public function __construct(
		public User $user,
	) {
	}

	/**
	 * Get the view that represent the component.
	 */
	#[Override]
	public function render(): View
	{
		return view( 'components.user.verified' );
	}
}
