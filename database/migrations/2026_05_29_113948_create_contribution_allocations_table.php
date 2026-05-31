<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run migrations.
     */
    public function up(): void
    {
        Schema::create('contribution_allocations', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | CONTRIBUTION
            |--------------------------------------------------------------------------
            */

            $table->foreignId('contribution_id')
                ->constrained()
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | SCHEDULE
            |--------------------------------------------------------------------------
            */

            $table->foreignId('contribution_schedule_id')
                ->constrained()
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | AMOUNT APPLIED
            |--------------------------------------------------------------------------
            */

            $table->decimal('amount', 12, 2);

            $table->timestamps();
        });
    }

    /**
     * Reverse migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contribution_allocations');
    }
};
