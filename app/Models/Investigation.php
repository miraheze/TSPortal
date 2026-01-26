<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Investigation extends Model
{
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
	 * Automatically convert these to Carbon date items
	 *
	 * @var array
	 */
	protected $casts = [
		'created' => 'datetime',
		'closed' => 'datetime',
	];

	/**
	 * Defines a relationship with reports leading to this investigation
	 */
	public function reports(): HasMany
	{
		return $this->hasMany( Report::class, 'investigation' );
	}

	/**
	 * Defines a relationship to the subject of this investigation
	 */
	public function subject(): BelongsTo
	{
		return $this->belongsTo( User::class, 'subject' );
	}

	/**
	 * Defines a relationship to the user assigned to investigate
	 */
	public function assigned(): BelongsTo
	{
		return $this->belongsTo( User::class, 'assigned' );
	}

	/**
	 * Defines a relationship with all events within this investigation
	 */
	public function events(): HasMany
	{
		return $this->hasMany( UserEvent::class, 'investigation' );
	}

	/**
	 * Defines a relationship with all appeals within this investigation
	 */
	public function appeals(): HasMany
	{
		return $this->hasMany( Appeal::class, 'investigation' );
	}

	/**
	 * Return a user object when querying the subject attribute
	 *
	 *
	 * @return User[]|Collection|Model|null
	 */
	public function getSubjectAttribute( int $id )
	{
		return User::findById( $id );
	}

	/**
	 * Return a user object when querying the assigned attribute
	 *
	 *
	 * @return User[]|Collection|Model|null
	 */
	public function getAssignedAttribute( int $id )
	{
		return User::findById( $id );
	}

	/**
	 * Create a new event for this investigation
	 *
	 *
	 * @return void
	 */
	public function newEvent( string $action, bool $userRecord, ?string $comment = null, ?User $actor = null )
	{
		if ( !( $action === 'comment' && !$comment ) ) {
			$subject = ( $userRecord ) ? $this->subject : null;

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
	 * Get the information for the open appeal
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
