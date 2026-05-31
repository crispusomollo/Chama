<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('loans', function (Blueprint $table) {

            if (!Schema::hasColumn('loans', 'duration_months')) {
                $table->integer('duration_months')->default(1);
            }

            if (!Schema::hasColumn('loans', 'disbursement_date')) {
                $table->date('disbursement_date')->nullable();
            }

            if (!Schema::hasColumn('loans', 'total_payable')) {
                $table->decimal('total_payable', 15, 2)->default(0);
            }

            if (!Schema::hasColumn('loans', 'monthly_installment')) {
                $table->decimal('monthly_installment', 15, 2)->default(0);
            }

            if (!Schema::hasColumn('loans', 'balance')) {
                $table->decimal('balance', 15, 2)->default(0);
            }

            if (!Schema::hasColumn('loans', 'credit_score')) {
                $table->integer('credit_score')->default(0);
            }

        });
    }

    public function down(): void
    {
        //
    }
};
