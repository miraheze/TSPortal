<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create( 'reports', function( Blueprint $table ) {
			$table->id();
			$table->foreignId( 'investigation' )->nullable()->default( null )->constrained( 'investigations' );
			$table->string( 'type' );
			$table->text( 'text' );
			$table->foreignId( 'user' )->constrained( 'users' );
			$table->foreignId( 'reporter' )->nullable()->constrained( 'users' );
			$table->timestamp( 'created' );
			$table->timestamp( 'reviewed' )->nullable()->default( null );
		} );
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists( 'reports' );
	}
}
