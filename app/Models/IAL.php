<?php

namespace App\Models;

use Database\Factories\IALFactory;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Attributes\Unguarded;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Table( name: 'ial', timestamps: false )]
#[Unguarded]
class IAL extends Model
{
	/** @use HasFactory<IALFactory> */
	use HasFactory;

	/**
	 * Get the attributes that should be cast.
	 *
	 * @return array<string, string>
	 */
	protected function casts(): array
	{
		return [
			'added' => 'datetime',
		];
	}

	/**
	 * Return a relationship between a IAL and the adding user.
	 */
	public function user(): BelongsTo
	{
		return $this->belongsTo( User::class, 'user' );
	}

	/**
	 * Return a user object when querying the user attribute.
	 */
	public function getUserAttribute( int $id ): User
	{
		return User::findById( $id );
	}
}
