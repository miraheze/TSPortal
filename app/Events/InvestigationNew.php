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
     */
    public Investigation $model;

    /**
     * Model name
     */
    public string $name = 'Investigation';

    /**
     * Model state
     */
    public string $state = 'created';

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Investigation $investigation)
    {
        $this->model = $investigation;
    }
}
