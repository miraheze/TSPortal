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
		Schema::create( 'transparency_reports', static function ( Blueprint $table ): void {
			$table->string( 'period', 7 );
			$table->string( 'category' );
			$table->string( 'type' );
			$table->string( 'outcome' )->default( '' );
			$table->integer( 'requested' )->default( 0 );
			$table->integer( 'completed' )->default( 0 );
			$table->primary( [ 'period', 'category', 'type', 'outcome' ] );
		} );
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists( 'transparency_reports' );
	}
};
