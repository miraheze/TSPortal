<?php

declare( strict_types = 1 );

namespace Database\Factories;

use App\Models\User;
use App\Models\UserEvent;
use Illuminate\Database\Eloquent\Factories\Factory;
use Override;

/**
 * @extends Factory<UserEvent>
 */
class UserEventFactory extends Factory
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
			'user' => User::class,
			'investigation' => null,
			'created' => now(),
			'created_by' => User::class,
			'action' => 'unknown',
			'comment' => null,
		];
	}
}
