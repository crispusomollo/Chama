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
            $table->foreignId('rule_id')
          ->nullable()
          ->after('chama_id')
          ->constrained('system_rules')
          ->nullOnDelete();
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
