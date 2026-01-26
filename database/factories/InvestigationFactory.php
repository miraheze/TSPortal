<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Investigation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvestigationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Investigation::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'subject' => User::class,
            'type' => 'other',
            'text' => null,
            'recommendation' => null,
            'explanation' => null,
            'created' => now(),
            'assigned' => null,
            'closed' => null,
        ];
    }
}
