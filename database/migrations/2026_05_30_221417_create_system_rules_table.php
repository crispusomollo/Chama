<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('system_rules', function (Blueprint $table) {

            $table->id();

            $table->date('effective_from');

            $table->decimal(
                'monthly_contribution',
                12,
                2
            );

            $table->decimal(
                'shares_allocation',
                12,
                2
            );

            $table->decimal(
                'insurance_allocation',
                12,
                2
            );

            $table->decimal(
                'penalty_amount',
                12,
                2
            )->default(100);

            $table->integer(
                'grace_days'
            )->default(5);

            $table->decimal(
                'interest_rate',
                8,
                2
            )->default(10);

            $table->integer(
                'loan_multiplier'
            )->default(3);

            $table->integer(
                'max_repayment_months'
            )->default(12);

            $table->boolean(
                'active'
            )->default(true);

            $table->text(
                'notes'
            )->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('system_rules');
    }
};
