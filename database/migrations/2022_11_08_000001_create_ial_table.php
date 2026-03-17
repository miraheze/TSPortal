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
		Schema::create( 'ial', static function ( Blueprint $table ): void {
			$table->id();
			$table->timestamp( 'added' );
			$table->foreignId( 'user' )->constrained( 'users' );
			$table->string( 'type' );
			$table->string( 'wiki' );
			$table->string( 'comments' );
			$table->string( 'dpa', 32 )->nullable();
			$table->foreignId( 'investigation' )->nullable()->constrained( 'investigations' );
			$table->foreign( 'dpa' )->references( 'id' )->on( 'dpas' );
		} );
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists( 'ial' );
	}
};
