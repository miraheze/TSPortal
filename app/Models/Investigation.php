<?php

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
	 * @var string[]
	 */
	protected $dates = [
		'created',
		'closed'
	];

	/**
	 * Defines a relationship with reports leading to this investigation
	 *
	 * @return HasMany
	 */
	public function reports(): HasMany
	{
		return $this->hasMany( Report::class, 'investigation' );
	}

	/**
	 * Defines a relationship to the subject of this investigation
	 *
	 * @return BelongsTo
	 */
	public function subject(): BelongsTo
	{
		return $this->belongsTo( User::class, 'subject' );
	}

	/**
	 * Defines a relationship to the user assigned to investigate
	 *
	 * @return BelongsTo
	 */
	public function assigned(): BelongsTo
	{
		return $this->belongsTo( User::class, 'assigned' );
	}

	/**
	 * Defines a relationship with all events within this investigation
	 *
	 * @return HasMany
	 */
	public function events(): HasMany
	{
		return $this->hasMany( UserEvent::class, 'investigation' );
	}

	/**
	 * Defines a relationship with all appeals within this investigation
	 *
	 * @return HasMany
	 */
	public function appeals(): HasMany
	{
		return $this->hasMany( Appeal::class, 'investigation' );
	}

	/**
	 * Return a user object when querying the subject attribute
	 *
	 * @param int $id
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
	 * @param int $id
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
	 * @param string $action
	 * @param bool $userRecord
	 * @param string|null $comment
	 * @param User|null $actor
	 *
	 * @return void
	 */
	public function newEvent( string $action, bool $userRecord, ?string $comment = null, ?User $actor = null )
	{
		if ( !( $action == 'comment' && !$comment ) ) {
			$subject = ( $userRecord ) ? $this->subject : null;

			UserEvent::factory()->create(
				[
					'user'          => $subject,
					'investigation' => $this,
					'created'       => now(),
					'created_by'    => $actor,
					'action'        => $action,
					'comment'       => $comment
				]
			);
		}
	}

	/**
	 * Get the information for the open appeal
	 *
	 * @return int|null
	 */
	public function openAppeal(): ?int
	{
		$appeals = $this->appeals;

		foreach ( $appeals as $appeal ) {
			if ( is_null( $appeal->reviewed ) ) {
				return $appeal->id;
			}
		}

		return null;
	}
}
