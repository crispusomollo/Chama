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
        Schema::create('contribution_schedules', function (Blueprint $table) {

        $table->id();

        $table->foreignId('borrower_id')
            ->constrained()
            ->cascadeOnDelete();

        $table->foreignId('chama_id')
            ->constrained()
            ->cascadeOnDelete();

        $table->string('period');

        $table->decimal('expected_amount', 12, 2)
            ->default(1000);

        $table->decimal('paid_amount', 12, 2)
            ->default(0);

        $table->decimal('balance', 12, 2)
            ->default(0);

        $table->decimal('penalty', 12, 2)
            ->default(0);

        $table->date('due_date');

        $table->enum('status', [
            'pending',
            'partial',
            'paid',
            'overdue'
        ])->default('pending');

        $table->timestamps();
    });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contribution_schedules');
    }
};
