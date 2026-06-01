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
		// $table->decimal('penalty', 12, 2)->default(0);
    if (!Schema::hasColumn('contribution_schedules', 'penalty')) {
    $table->decimal('penalty', 12, 2)->default(0);
}
    // $table->boolean('penalty_applied')->default(false);
    if (!Schema::hasColumn('contribution_schedules', 'penalty_applied')) {
    $table->decimal('penalty_applied', 12, 2)->default(false);
}
    // $table->date('last_penalty_date')->nullable();
    if (!Schema::hasColumn('contribution_schedules', 'last_penalty_date')) {
    $table->decimal('last_penalty_date', 12, 2)->nullable;
}
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
