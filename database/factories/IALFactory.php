<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\IAL;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Factories\Factory;

class IALFactory extends Factory
{
	/**
	 * The name of the factory's corresponding model.
	 *
	 * @var string
	 */
	protected $model = IAL::class;

	/**
	 * Define the model's default state.
	 *
	 * @throws Exception
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
