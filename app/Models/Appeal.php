<?php

declare( strict_types = 1 );

namespace App\Models;

use Database\Factories\AppealFactory;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Attributes\Unguarded;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Table( timestamps: false )]
#[Unguarded]
class Appeal extends Model
{
	/** @use HasFactory<AppealFactory> */
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
	 * Defines a relationship to the investigation of this appeal.
	 */
	public function investigation(): BelongsTo
	{
		return $this->belongsTo( Investigation::class, 'investigation' );
	}

	/**
	 * Defines a relationship to the user assigned to the appeal.
	 */
	public function assigned(): BelongsTo
	{
		return $this->belongsTo( User::class, 'assigned' );
	}

	/**
	 * Return a user object when querying the assigned attribute.
	 */
	public function getAssignedAttribute( int $id ): User
	{
		return User::findById( $id );
	}

	/**
	 * Return an investigation object when querying the investigation attribute.
	 */
	public function getInvestigationAttribute( int $id ): Investigation
	{
		return Investigation::find( $id );
	}
}
