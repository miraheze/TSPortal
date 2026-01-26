<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppealsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
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
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists( 'appeals' );
	}
}
