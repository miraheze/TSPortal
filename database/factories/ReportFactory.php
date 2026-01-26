<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Report;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReportFactory extends Factory
{
	/**
	 * The name of the factory's corresponding model.
	 *
	 * @var string
	 */
	protected $model = Report::class;

	/**
	 * Define the model's default state.
	 */
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
