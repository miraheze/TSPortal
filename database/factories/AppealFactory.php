<?php

declare( strict_types = 1 );

namespace Database\Factories;

use App\Models\Appeal;
use App\Models\Investigation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Appeal>
 */
class AppealFactory extends Factory
{
	/**
	 * Define the model's default state.
	 *
	 * @return array<string, mixed>
	 */
	public function definition(): array
	{
		return [
			'investigation' => Investigation::class,
			'type' => 'new-information',
			'text' => null,
			'review' => null,
			'assigned' => null,
			'outcome' => null,
			'created' => now(),
			'reviewed' => null,
		];
	}
}
