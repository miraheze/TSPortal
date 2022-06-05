<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvestigationsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create( 'investigations', function( Blueprint $table ) {
			$table->id();
			$table->foreignId( 'subject' )->constrained( 'users' );
			$table->string( 'type' );
			$table->text( 'text' )->nullable();
			$table->text( 'recommendation' )->nullable();
			$table->text( 'explanation' )->nullable();
			$table->timestamp( 'created' );
			$table->string( 'assigned' );
			$table->timestamp( 'closed' )->nullable()->default( null );
		} );
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists( 'investigations' );
	}
}
