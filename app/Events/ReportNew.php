<?php

namespace App\Events;

use App\Models\Report;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ReportNew
{
	use Dispatchable, InteractsWithSockets, SerializesModels;

	/**
	 * Model for event
	 *
	 * @var Report
	 */
	public Report $model;

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
