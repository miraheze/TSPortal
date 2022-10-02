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
	 * Model for event
	 *
	 * @var Appeal
	 */
	public Appeal $model;

	/**
	 * Model name
	 *
	 * @var string
	 */
	public string $name = 'Appeal';

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
	public function __construct( Appeal $appeal )
	{
		$this->model = $appeal;
	}
}
