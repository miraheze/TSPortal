<?php

namespace App\Events;

use App\Models\Investigation;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class InvestigationNew
{
	use Dispatchable, InteractsWithSockets, SerializesModels;

	/**
	 * Model for event
	 *
	 * @var Investigation
	 */
	public Investigation $model;

	/**
	 * Model name
	 *
	 * @var string
	 */
	public string $name = 'Investigation';

	/**
	 * Model state
	 *
	 * @var string
	 */
	public string $state = 'created';

	/**
	 * Create a new event instance.
	 *
	 * @return void
	 */
	public function __construct( Investigation $investigation )
	{
		$this->model = $investigation;
	}
}
