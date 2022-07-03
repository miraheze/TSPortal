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
	 * Create a new event instance.
	 *
	 * @return void
	 */
	public function __construct( Investigation $investigation )
	{
		$this->model = $investigation;
	}
}
