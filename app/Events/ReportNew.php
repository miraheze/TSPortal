<?php

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
	 *
	 * @var Report
	 */
	public Report $model;

	/**
	 * Model name
	 *
	 * @var string
	 */
	public string $name = 'Report';

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
	public function __construct( Report $report )
	{
		$this->model = $report;
	}
}
