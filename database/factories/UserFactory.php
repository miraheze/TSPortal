<?php

declare( strict_types = 1 );

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Override;
use function now;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
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
			'created' => now(),
			'username' => 'username',
			'user_verified' => false,
			'standing' => 1,
			'flags' => [],
		];
	}
}
