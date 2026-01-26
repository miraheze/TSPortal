<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\Investigation;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class InvestigationClosed
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
    public string $state;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Investigation $investigation, bool $closed)
    {
        $this->model = $investigation;
        $this->state = ($closed) ? 'closed' : 'reopened';
    }
}
