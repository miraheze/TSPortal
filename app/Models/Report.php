<?php

namespace App\Models;

use Database\Factories\ReportFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Report extends Model
{
	/** @use HasFactory<ReportFactory> */
	use HasFactory;

	/**
	 * Disable standard timestamps
	 *
	 * @var bool
	 */
	public $timestamps = false;

	/**
	 * Allow mass-assignment of all variables
	 *
	 * @var array
	 */
	protected $guarded = [];

	/**
	 * Get the attributes that should be cast.
	 *
	 * @return array<string, string>
	 */
	protected function casts(): array
	{
		return [
			'created' => 'datetime',
			'reviewed' => 'datetime',
		];
	}

	/**
	 * Defines a relationship with the investigation this report creates.
	 */
	public function investigation(): BelongsTo
	{
		return $this->belongsTo( Investigation::class, 'investigation' );
	}

	/**
	 * Defines a relationship with the reporter of this report.
	 */
	public function reporter(): BelongsTo
	{
		return $this->belongsTo( User::class, 'reporter' );
	}

	/**
	 * Defines a relationship with the subject of this report.
	 */
	public function user(): BelongsTo
	{
		return $this->belongsTo( User::class, 'user' );
	}

	/**
	 * Return a user object when querying the reporter attribute.
	 */
	public function getReporterAttribute( int $id ): User
	{
		return User::findById( $id );
	}

	/**
	 * Return a user object when querying the user attribute.
	 */
	public function getUserAttribute( int $id ): User
	{
		return User::findById( $id );
	}
}
