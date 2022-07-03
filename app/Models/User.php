<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
	use HasApiTokens, HasFactory, Notifiable;

	/**
	 * Standing constants to ensure consistency
	 *
	 * @var int[]
	 */
	public const STANDING = [
		'GOOD'       => 1,
		'SANCTIONED' => 0,
		'BANNED'     => -1
	];

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
	 * Casts an attribute by default
	 *
	 * @var string[]
	 */
	protected $casts = [
		'flags' => 'array'
	];

	/**
	 * Automatically convert these to Carbon date items
	 *
	 * @var string[]
	 */
	protected $dates = [
		'created'
	];

	/**
	 * Table associated with this model
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * All flags available to be assigned to users
	 *
	 * @var array|string[]
	 */
	private array $allFlags = [
		'login-disabled',
		'ts',
		'user-manager'
	];

	/**
	 * Find a user by username, or create a new user with the username
	 *
	 * @param string $username
	 * @param bool $oauth
	 *
	 * @return Model|mixed
	 */
	public static function findOrCreate( string $username, bool $oauth = false )
	{
		$authUser = static::all()->firstWhere( 'username', $username );

		if ( !$authUser ) {
			$authUser = static::factory()->createOne(
				[
					'username' => $username,
				]
			);
		}

		if ( $oauth ) {
			$authUser->update( [
				'user_verified' => true
			] );
		}

		return $authUser;
	}

	/**
	 * Find a user by ID
	 *
	 * @param int $id
	 *
	 * @return User[]|Collection|Model|null
	 */
	public static function findById( int $id )
	{
		return static::all()->find( $id );
	}

	/**
	 * Defines a relationship with all investigations the user is a subject of
	 *
	 * @return HasMany
	 */
	public function investigations(): HasMany
	{
		return $this->hasMany( Investigation::class, 'subject' );
	}

	/**
	 * Defines a relationship with all reports the user is the subject of
	 *
	 * @return HasMany
	 */
	public function reports(): HasMany
	{
		return $this->hasMany( Report::class, 'user' );
	}

	/**
	 * Defines a relationship with all reports the user has made
	 *
	 * @return HasMany
	 */
	public function reportsMade(): HasMany
	{
		return $this->hasMany( Report::class, 'reporter' );
	}

	/**
	 * Defines a relationship with all events associated with the user
	 *
	 * @return HasMany
	 */
	public function events(): HasMany
	{
		return $this->hasMany( UserEvent::class, 'user' );
	}

	/**
	 * Checks whether the user has a certain flag
	 *
	 * @param string $flag
	 *
	 * @return bool
	 */
	public function hasFlag( string $flag ): bool
	{
		return in_array( $flag, $this->flags );
	}

	/**
	 * Returns all flags that are available for the user
	 *
	 * @return array|string[]
	 */
	public function allFlags(): array
	{
		return $this->allFlags;
	}

	/**
	 * Updates a users flags
	 *
	 * @param array $newFlags
	 * @param User|null $actor
	 *
	 * @return void
	 */
	public function updateFlags( array $newFlags, ?User $actor = null )
	{
		$this->flags = $newFlags;
		$this->save();

		$this->newEvent( 'update-flags', json_encode( $this->flags ), $actor );
	}

	/**
	 * Creates a new event associated with the user
	 *
	 * @param string $action
	 * @param string|null $comment
	 * @param User|null $actor
	 *
	 * @return void
	 */
	public function newEvent( string $action, ?string $comment = null, ?User $actor = null )
	{
		UserEvent::factory()->create(
			[
				'user'       => $this,
				'created'    => now(),
				'created_by' => $actor,
				'action'     => $action,
				'comment'    => $comment
			]
		);
	}

	/**
	 * Retrieve the current user standing
	 *
	 * @return string
	 */
	public function getStanding(): string
	{
		return ucfirst( strtolower( array_flip( self::STANDING )[$this->standing] ) );
	}

	/**
	 * Updating standing based on recorded event
	 *
	 * @return void
	 */
	public function updateStanding( string $event )
	{
		if ( in_array( $event, [ 'ban-partial', 'ban-full' ] ) && ( $this->standing > self::STANDING['BANNED'] ) ) {
			$this->update( [
				'standing' => self::STANDING['BANNED']
			] );
		} elseif ( in_array( $event, [ 'nd-checkuser', 'nd-protect', 'd-checkuser' ] ) ) {
			return null;
		} elseif ( $this->standing > self::STANDING['SANCTIONED'] ) {
			$this->update( [
				'standing' => self::STANDING['SANCTIONED']
			] );
		}
	}
}
