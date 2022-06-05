<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\UserEvent;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserEventFactory extends Factory
{
	/**
	 * The name of the factory's corresponding model.
	 *
	 * @var string
	 */
	protected $model = UserEvent::class;

	/**
	 * Define the model's default state.
	 *
	 * @return array
	 */
	public function definition(): array
	{
		return [
			'user'          => User::class,
			'investigation' => null,
			'created'       => now(),
			'created_by'    => User::class,
			'action'        => 'unknown',
			'comment'       => null
		];
	}
}
