<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIALTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create( 'ial', function( Blueprint $table ) {
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
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists( 'ial' );
	}
}
