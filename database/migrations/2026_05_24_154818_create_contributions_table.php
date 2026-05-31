<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
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

            $table->decimal('amount', 12, 2);

            $table->date('contribution_date');

            $table->string('period');
            // example: 2026-05

            $table->string('type')
                  ->default('monthly');

            $table->string('status')
                  ->default('paid');

            $table->text('notes')->nullable();

            $table->timestamps();

            $table->index(['borrower_id', 'period']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contributions');
    }
};
