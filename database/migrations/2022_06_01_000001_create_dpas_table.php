<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDPASTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dpas', function (Blueprint $table) {
            $table->string('id', 32);
            $table->timestamp('filed');
            $table->foreignId('user')->constrained('users');
            $table->string('underage')->nullable();
            $table->boolean('statutory')->default(false);
            $table->timestamp('completed')->nullable();
            $table->string('reject')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dpas');
    }
}
