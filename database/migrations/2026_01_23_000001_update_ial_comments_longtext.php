<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateIALCommentsLongtext extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('ial', function (Blueprint $table): void {
            $table->longText('comments')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ial', function (Blueprint $table): void {
            $table->string('comments')->change();
        });
    }
}
