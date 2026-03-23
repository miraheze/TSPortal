<?php

declare( strict_types = 1 );

namespace Database\Factories;

use App\Models\DPA;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Override;
use function now;
use function random_bytes;
use function sha1;
use function substr;

/**
 * @extends Factory<DPA>
 */
class DPAFactory extends Factory
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
