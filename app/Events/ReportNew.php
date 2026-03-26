<?php

declare( strict_types = 1 );

namespace App\Events;

use App\Models\Report;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ReportNew
{
	use Dispatchable, InteractsWithSockets, SerializesModels;

	/**
	 * Model name.
	 */
	public string $name = 'Report';

	/**
	 * Model state.
	 */
	public string $state = 'created';

	/**
	 * Create a new event instance.
	 */
	public function __construct(
		public Report $model,
	) {
	}
}
