<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserEvent extends Model
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
	];

	/**
	 * Table associated with the model
	 *
	 * @var string
	 */
	protected $table = 'users_events';

	/**
	 * Defines a relationship with the user the event is for
	 *
	 * @return BelongsTo
	 */
	public function user(): BelongsTo
	{
		return $this->belongsTo( User::class, 'user' );
	}

	/**
	 * Defines a relationship with the investigation an event is for
	 *
	 * @return BelongsTo
	 */
	public function investigation(): BelongsTo
	{
		return $this->belongsTo( Investigation::class, 'investigation' );
	}

	/**
	 * Returns a User object for the created_by attribute
	 *
	 * @param int|null $user
	 *
	 * @return User|User[]|Collection|Model|null
	 */
	public function getCreatedByAttribute( ?int $user )
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
