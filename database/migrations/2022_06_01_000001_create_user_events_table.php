<?php

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
		Schema::create( 'users_events', function ( Blueprint $table ) {
			$table->foreignId( 'user' )->nullable()->constrained( 'users' );
			$table->foreignId( 'investigation' )->nullable()->constrained( 'investigations' );
			$table->timestamp( 'created' );
			$table->foreignId( 'created_by' )->nullable()->constrained( 'users' );
			$table->string( 'action' );
			$table->text( 'comment' )->nullable();
		} );
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists( 'user_events' );
	}
};
