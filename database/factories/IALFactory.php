<?php

declare( strict_types = 1 );

namespace Database\Factories;

use App\Models\IAL;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<IAL>
 */
class IALFactory extends Factory
{
	/**
	 * Define the model's default state.
	 *
	 * @return array<string, mixed>
	 */
	public function definition(): array
	{
		return [
			'added' => now(),
			'user' => User::class,
			'type' => 'log',
			'wiki' => 'wiki',
			'comments' => null,
			'dpa' => null,
			'investigation' => null,
		];
	}
}
