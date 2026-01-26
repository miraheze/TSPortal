<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\DPA;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Factories\Factory;

class DPAFactory extends Factory
{
	/**
	 * The name of the factory's corresponding model.
	 *
	 * @var string
	 */
	protected $model = DPA::class;

	/**
	 * Define the model's default state.
	 *
	 * @throws Exception
	 */
	public function definition(): array
	{
		return [
			'id' => substr( sha1( random_bytes( 10 ) ), 0, 32 ),
			'filed' => now(),
			'user' => User::class,
			'underage' => null,
			'statutory' => false,
			'completed' => null,
			'reject' => null,
		];
	}
}
