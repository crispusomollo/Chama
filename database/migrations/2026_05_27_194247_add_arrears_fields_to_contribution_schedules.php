<?php

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
        Schema::table('contribution_schedules', function (Blueprint $table) {
            $table->decimal('penalty', 12, 2)->default(0);
    $table->boolean('penalty_applied')->default(false);
    $table->date('last_penalty_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contribution_schedules', function (Blueprint $table) {
            //
        });
    }
};
