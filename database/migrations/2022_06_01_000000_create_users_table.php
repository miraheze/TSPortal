<?php

declare( strict_types = 1 );

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		Schema::create( 'users', static function ( Blueprint $table ): void {
			$table->id();
			$table->timestamp( 'created' );
			$table->string( 'username' );
			$table->boolean( 'user_verified' )->default( false );
			$table->tinyInteger( 'standing' )->default( 0 );
			$table->json( 'flags' )->default( '[]' );
		} );
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists( 'users' );
	}
};
