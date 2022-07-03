<?php

namespace App\Events;

use App\Models\DPA;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DPANew
{
	use Dispatchable, InteractsWithSockets, SerializesModels;

	/**
	 * Model for event
	 *
	 * @var DPA
	 */
	public DPA $model;

	/**
	 * Create a new event instance.
	 *
	 * @return void
	 */
	public function __construct( DPA $dpa )
	{
		$this->model = $dpa;
	}
}
