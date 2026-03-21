<?php

declare( strict_types = 1 );

namespace App\Models;

use Database\Factories\UserEventFactory;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Attributes\Unguarded;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Table( name: 'users_events', timestamps: false )]
#[Unguarded]
class UserEvent extends Model
{
	/** @use HasFactory<UserEventFactory> */
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
			$user = new User;
			$user->username = 'System';
		} else {
			$user = User::findById( $user );
		}

		return $user;
	}
}
