<?php

namespace App\View\Components\User;

use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Verified extends Component
{
	/**
	 * User we want to verify
	 *
	 * @var User
	 */
	public User $user;

	/**
	 * Create a new component instance.
	 *
	 * @return void
	 */
	public function __construct( User $user )
	{
		$this->user = $user;
	}

	/**
	 * Get the view that represent the component.
	 *
	 * @return Application|Factory|View
	 */
	public function render()
	{
		return view( 'components.user.verified' );
	}
}
