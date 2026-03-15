<?php

namespace App\Events;

use App\Models\Investigation;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class InvestigationReopened
{
	use Dispatchable, InteractsWithSockets, SerializesModels;

	/**
	 * Model for event.
	 */
	public Investigation $model;

	/**
	 * Model name.
	 */
	public string $name = 'Investigation';

	/**
	 * Model state.
	 */
	public string $state = 'reopened';

	/**
	 * Create a new event instance.
	 */
	public function __construct( Investigation $investigation )
	{
		$this->model = $investigation;
	}
}
