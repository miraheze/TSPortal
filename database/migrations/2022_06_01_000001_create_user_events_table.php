<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('users_events', function (Blueprint $table): void {
            $table->foreignId('user')->nullable()->constrained('users');
            $table->foreignId('investigation')->nullable()->constrained('investigations');
            $table->timestamp('created');
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->string('action');
            $table->text('comment')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('user_events');
    }
}
