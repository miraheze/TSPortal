<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DPA extends Model
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
		'filed' => 'datetime',
		'completed' => 'datetime',
	];

	/**
	 * Type casting the main ID
	 *
	 * @var string
	 */
	protected $keyType = 'string';

	/**
	 * Table associated with model
	 *
	 * @var string
	 */
	protected $table = 'dpas';

	/**
	 * Return a relationship between a DPA and the subject user
	 */
	public function user(): BelongsTo
	{
		return $this->belongsTo( User::class, 'user' );
	}

	/**
	 * Return a user object when querying the user attribute
	 *
	 *
	 * @return User[]|Collection|Model|null
	 */
	public function getUserAttribute( int $id )
	{
		return User::findById( $id );
	}
}
