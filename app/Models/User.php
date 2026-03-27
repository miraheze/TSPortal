<?php

declare( strict_types = 1 );

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Attributes\Unguarded;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Override;
use function array_flip;
use function in_array;
use function json_encode;
use function now;
use function strtolower;
use function ucfirst;

#[Table( name: 'users', timestamps: false )]
#[Unguarded]
class User extends Authenticatable
{
	/** @use HasFactory<UserFactory> */
	use HasFactory, Notifiable;

	/**
	 * Standing constants to ensure consistency.
	 *
	 * @var array<string, int>
	 */
	private const array STANDING = [
		'GOOD' => 1,
		'SANCTIONED' => 0,
		'BANNED' => -1,
	];

	/**
	 * All flags available to be assigned to users.
	 *
	 * @var string[]
	 */
	private array $allFlags = [
		'login-disabled',
		'ts',
		'user-manager',
	];

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
			'flags' => 'array',
		];
	}

	/**
	 * Find a user by username, or create a new user with the username.
	 */
	public static function findOrCreate( string $username, bool $oauth = false ): self
	{
		$authUser = self::firstWhere( 'username', $username );
		if ( !$authUser ) {
			$authUser = self::factory()->createOne(
				[ 'username' => $username ]
			);
		}

		if ( $oauth ) {
			$authUser->update( [ 'user_verified' => true ] );
		}

		return $authUser;
	}

	/**
	 * Find a user by ID.
	 */
	public static function findById( int $id ): self
	{
		return self::find( $id );
	}

	/**
	 * Defines a relationship with all investigations the user is a subject of.
	 */
	public function investigations(): HasMany
	{
		return $this->hasMany( Investigation::class, 'subject' );
	}

	/**
	 * Defines a relationship with all reports the user is the subject of.
	 */
	public function reports(): HasMany
	{
		return $this->hasMany( Report::class, 'user' );
	}

	/**
	 * Defines a relationship with all reports the user has made.
	 */
	public function reportsMade(): HasMany
	{
		return $this->hasMany( Report::class, 'reporter' );
	}

	/**
	 * Defines a relationship with all events associated with the user.
	 */
	public function events(): HasMany
	{
		return $this->hasMany( UserEvent::class, 'user' );
	}

	/**
	 * Checks whether the user has a certain flag.
	 */
	public function hasFlag( string $flag ): bool
	{
		return in_array( $flag, $this->flags, true );
	}

	/**
	 * Returns all flags that are available for the user.
	 *
	 * @return string[]
	 */
	public function allFlags(): array
	{
		return $this->allFlags;
	}

	/**
	 * Updates a users flags.
	 */
	public function updateFlags( array $newFlags, ?User $actor = null ): void
	{
		if ( $newFlags === $this->flags ) {
			return;
		}

		$this->flags = $newFlags;
		$this->save();

		$this->newEvent( 'update-flags', json_encode( $this->flags ), $actor );
	}

	/**
	 * Creates a new event associated with the user.
	 */
	public function newEvent( string $action, ?string $comment = null, ?User $actor = null ): void
	{
		if ( $comment === '' ) {
			$comment = null;
		}

		UserEvent::factory()->create(
			[
				'user' => $this,
				'created' => now(),
				'created_by' => $actor,
				'action' => $action,
				'comment' => $comment,
			]
		);
	}

	/**
	 * Retrieve the current user standing.
	 */
	public function getStanding(): string
	{
		return ucfirst( strtolower( array_flip( self::STANDING )[$this->standing] ) );
	}

	/**
	 * Updating standing based on recorded event.
	 */
	public function updateStanding( string $event ): void
	{
		if ( in_array( $event, [ 'ban-partial', 'ban-full' ], true ) && ( $this->standing > self::STANDING['BANNED'] ) ) {
			$this->update( [
				'standing' => self::STANDING['BANNED'],
			] );
		} elseif ( in_array( $event, [ 'nd-checkuser', 'nd-protect', 'd-checkuser' ], true ) ) {
			return;
		} elseif ( $this->standing > self::STANDING['SANCTIONED'] ) {
			$this->update( [
				'standing' => self::STANDING['SANCTIONED'],
			] );
		}
	}
}
