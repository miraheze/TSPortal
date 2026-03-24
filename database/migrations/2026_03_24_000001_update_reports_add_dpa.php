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
		Schema::table( 'reports', static function ( Blueprint $table ): void {
			$table->string( 'dpa', 32 )->nullable()->after( 'investigation' );
			$table->foreign( 'dpa' )->references( 'id' )->on( 'dpas' );
		} );
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::table( 'reports', static function ( Blueprint $table ): void {
			$table->dropForeign( 'dpa' );
			$table->dropColumn( 'dpa' );
		} );
	}
};
