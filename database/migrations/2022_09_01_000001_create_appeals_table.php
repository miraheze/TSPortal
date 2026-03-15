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
		Schema::create( 'appeals', function ( Blueprint $table ) {
			$table->id();
			$table->foreignId( 'investigation' )->constrained( 'investigations' );
			$table->string( 'type' );
			$table->text( 'text' );
			$table->text( 'review' )->nullable()->default( null );
			$table->foreignId( 'assigned' )->nullable()->constrained( 'users' );
			$table->text( 'outcome' )->nullable()->default( null );
			$table->timestamp( 'created' );
			$table->timestamp( 'reviewed' )->nullable()->default( null );
		} );
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists( 'appeals' );
	}
};
