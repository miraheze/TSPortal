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
	 * Model name
	 *
	 * @var string
	 */
	public string $name = 'DPA';

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
	public function __construct( DPA $dpa )
	{
		$this->model = $dpa;
	}
}
