<?php

declare(strict_types=1);

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
     */
    public DPA $model;

    /**
     * Model name
     */
    public string $name = 'DPA';

    /**
     * Model state
     */
    public string $state = 'created';

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(DPA $dpa)
    {
        $this->model = $dpa;
    }
}
