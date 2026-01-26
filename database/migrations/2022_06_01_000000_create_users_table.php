<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create( 'users', function ( Blueprint $table ) {
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
	 *
	 * @return void
	 */
	public function down()
	{
		//
	}
}
