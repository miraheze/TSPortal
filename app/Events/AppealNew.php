<?php

namespace App\Events;

use App\Models\Appeal;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AppealNew
{
	use Dispatchable, InteractsWithSockets, SerializesModels;

	/**
	 * Model name.
	 */
	public string $name = 'Appeal';

	/**
	 * Model state.
	 */
	public string $state = 'created';

	/**
	 * Create a new event instance.
	 */
	public function __construct(
		public Appeal $model,
	) {
	}
}
