<?php

declare( strict_types = 1 );

namespace App\Models;

use Database\Factories\ReportFactory;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Attributes\Unguarded;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Table( timestamps: false )]
#[Unguarded]
class Report extends Model
{
	/** @use HasFactory<ReportFactory> */
	use HasFactory;

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
