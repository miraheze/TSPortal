<?php

declare( strict_types = 1 );

namespace App\Events;

use App\Models\Investigation;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class InvestigationNew
{
	use Dispatchable, InteractsWithSockets, SerializesModels;

	/**
	 * Model name.
	 */
	public string $name = 'Investigation';

	/**
	 * Model state.
	 */
	public string $state = 'created';

	/**
	 * Create a new event instance.
	 */
	public function __construct(
		public Investigation $model,
	) {
	}
}
