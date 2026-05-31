<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('insurance_ledgers', function (Blueprint $table) {

            $table->id();

            $table->foreignId('borrower_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('contribution_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->string('transaction_type')
                ->default('contribution');

            $table->string('description');

            $table->decimal('credit', 12, 2)
                ->default(0);

            $table->decimal('debit', 12, 2)
                ->default(0);

            $table->decimal('balance', 12, 2)
                ->default(0);

            $table->date('transaction_date');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('insurance_ledgers');
    }
};
