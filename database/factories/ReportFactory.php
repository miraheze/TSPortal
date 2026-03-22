<?php

declare( strict_types = 1 );

namespace Database\Factories;

use App\Models\Report;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Override;

/**
 * @extends Factory<Report>
 */
class ReportFactory extends Factory
{
	/**
	 * Define the model's default state.
	 *
	 * @return array<string, mixed>
	 */
	#[Override]
	public function definition(): array
	{
		return [
			'investigation' => null,
			'type' => 'other',
			'text' => null,
			'user' => User::class,
			'reporter' => User::class,
			'created' => now(),
			'reviewed' => null,
		];
	}
}
