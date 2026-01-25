<?php

namespace App\View\Components\User;

use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Flags extends Component {
	/**
	 * User flags
	 *
	 * @var array
	 */
	public array $flags;

	/**
	 * Create a new component instance.
	 *
	 * @return void
	 */
	public function __construct( User $user ) {
		$this->flags = $user->flags;
	}

	/**
	 * Get the view that represent the component.
	 *
	 * @return Application|Factory|View
	 */
	public function render() {
		return view( 'components.user.flags' );
	}
}
