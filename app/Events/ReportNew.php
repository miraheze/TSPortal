<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\Report;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ReportNew
{
	use Dispatchable;
	use InteractsWithSockets;
	use SerializesModels;

	/**
	 * Model for event
	 */
	public Report $model;

	/**
	 * Model name
	 */
	public string $name = 'Report';

	/**
	 * Model state
	 */
	public string $state = 'created';

	/**
	 * Create a new event instance.
	 *
	 * @return void
	 */
	public function __construct( Report $report )
	{
		$this->model = $report;
	}
}
