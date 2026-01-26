<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDPASTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('dpas', function (Blueprint $table): void {
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
     */
    public function down(): void
    {
        Schema::dropIfExists('dpas');
    }
}
