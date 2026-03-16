<?php

namespace App\Models;

use Database\Factories\UserEventFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserEvent extends Model
{
	/** @use HasFactory<UserEventFactory> */
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
	 * Table associated with the model
	 *
	 * @var string
	 */
	protected $table = 'users_events';

	/**
	 * Get the attributes that should be cast.
	 *
	 * @return array<string, string>
	 */
	protected function casts(): array
	{
		return [
			'created' => 'datetime',
		];
	}

	/**
	 * Defines a relationship with the user the event is for.
	 */
	public function user(): BelongsTo
	{
		return $this->belongsTo( User::class, 'user' );
	}

	/**
	 * Defines a relationship with the investigation an event is for.
	 */
	public function investigation(): BelongsTo
	{
		return $this->belongsTo( Investigation::class, 'investigation' );
	}

	/**
	 * Returns a User object for the created_by attribute.
	 */
	public function getCreatedByAttribute( ?int $user ): User
	{
		if ( !$user ) {
			$user = new User();
			$user->username = 'System';
		} else {
			$user = User::findById( $user );
		}

		return $user;
	}
}
