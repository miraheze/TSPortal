<?php

declare( strict_types = 1 );

namespace App\Models;

use Database\Factories\InvestigationFactory;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Attributes\Unguarded;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Override;

#[Table( timestamps: false )]
#[Unguarded]
class Investigation extends Model
{
	/** @use HasFactory<InvestigationFactory> */
	use HasFactory;

	/**
	 * Get the attributes that should be cast.
	 *
	 * @return array<string, string>
	 */
	#[Override]
	protected function casts(): array
	{
		return [
			'created' => 'datetime',
			'closed' => 'datetime',
		];
	}

	/**
	 * Defines a relationship with reports leading to this investigation.
	 */
	public function reports(): HasMany
	{
		return $this->hasMany( Report::class, 'investigation' );
	}

	/**
	 * Defines a relationship to the subject of this investigation.
	 */
	public function subject(): BelongsTo
	{
		return $this->belongsTo( User::class, 'subject' );
	}

	/**
	 * Defines a relationship to the user assigned to investigate.
	 */
	public function assigned(): BelongsTo
	{
		return $this->belongsTo( User::class, 'assigned' );
	}

	/**
	 * Defines a relationship with all events within this investigation.
	 */
	public function events(): HasMany
	{
		return $this->hasMany( UserEvent::class, 'investigation' );
	}

	/**
	 * Defines a relationship with all appeals within this investigation.
	 */
	public function appeals(): HasMany
	{
		return $this->hasMany( Appeal::class, 'investigation' );
	}

	/**
	 * Return a user object when querying the subject attribute.
	 */
	public function getSubjectAttribute( int $id ): User
	{
		return User::findById( $id );
	}

	/**
	 * Return a user object when querying the assigned attribute.
	 */
	public function getAssignedAttribute( int $id ): User
	{
		return User::findById( $id );
	}

	/**
	 * Create a new event for this investigation.
	 */
	public function newEvent( string $action, bool $userRecord, ?string $comment = null, ?User $actor = null ): void
	{
		if ( !( $action === 'comment' && !$comment ) ) {
			$subject = $userRecord ? $this->subject : null;
			UserEvent::factory()->create(
				[
					'user' => $subject,
					'investigation' => $this,
					'created' => now(),
					'created_by' => $actor,
					'action' => $action,
					'comment' => $comment,
				]
			);
		}
	}

	/**
	 * Get the information for the open appeal.
	 */
	public function openAppeal(): ?int
	{
		$appeals = $this->appeals;
		foreach ( $appeals as $appeal ) {
			if ( $appeal->reviewed === null ) {
				return $appeal->id;
			}
		}

		return null;
	}
}
