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
        Schema::create('contributions', function (Blueprint $table) {
        $table->id();

        $table->foreignId('borrower_id')
            ->constrained()
            ->onDelete('cascade');

        $table->foreignId('chama_id')
            ->constrained()
            ->onDelete('cascade');

        $table->decimal('amount', 12, 2)->default(0);

        $table->date('contribution_date')->nullable();

        $table->string('period'); 
        // format: 2026-05

        $table->string('type')->default('monthly');

        $table->string('status')->default('pending');
        // pending | paid | overdue

        $table->text('notes')->nullable();

        $table->timestamps();

        $table->index(['period', 'borrower_id']);
        });
      }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contributions');
    }
};
