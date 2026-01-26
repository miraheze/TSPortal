<?php

namespace Database\Factories;

use App\Models\Appeal;
use App\Models\Investigation;
use Illuminate\Database\Eloquent\Factories\Factory;

class AppealFactory extends Factory
{
	/**
	 * The name of the factory's corresponding model.
	 *
	 * @var string
	 */
	protected $model = Appeal::class;

	/**
	 * Define the model's default state.
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
