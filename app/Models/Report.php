<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Report extends Model
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
		'reviewed' => 'datetime',
	];

	/**
	 * Defines a relationship with the investigation this report creates
	 *
	 * @return BelongsTo
	 */
	public function investigation(): BelongsTo
	{
		return $this->belongsTo( Investigation::class, 'investigation' );
	}

	/**
	 * Defines a relationship with the reporter of this report
	 *
	 * @return BelongsTo
	 */
	public function reporter(): BelongsTo
	{
		return $this->belongsTo( User::class, 'reporter' );
	}

	/**
	 * Defines a relationship with the subject of this report
	 *
	 * @return BelongsTo
	 */
	public function user(): BelongsTo
	{
		return $this->belongsTo( User::class, 'user' );
	}

	/**
	 * Return a user object when querying the reporter attribute
	 *
	 * @param int $id
	 *
	 * @return User[]|Collection|Model|null
	 */
	public function getReporterAttribute( int $id )
	{
		return User::findById( $id );
	}

	/**
	 * Return a user object when querying the user attribute
	 *
	 * @param int $id
	 *
	 * @return User[]|Collection|Model|null
	 */
	public function getUserAttribute( int $id )
	{
		return User::findById( $id );
	}
}
