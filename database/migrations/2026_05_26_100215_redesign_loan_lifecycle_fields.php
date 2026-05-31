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
        Schema::table('loans', function (Blueprint $table) {

        /*
        |--------------------------------------------------------------------------
        | REMAINING FINANCIAL FIELDS
        |--------------------------------------------------------------------------
        */

        $table->decimal('total_repaid', 12, 2)
            ->default(0)
            ->after('total_payable');

        $table->decimal('outstanding_balance', 12, 2)
            ->default(0)
            ->after('total_repaid');

        /*
        |--------------------------------------------------------------------------
        | APPROVAL + DISBURSEMENT AUDIT
        |--------------------------------------------------------------------------
        */

        $table->timestamp('approved_at')
            ->nullable()
            ->after('outstanding_balance');

        $table->foreignId('approved_by')
            ->nullable()
            ->after('approved_at')
            ->constrained('users')
            ->nullOnDelete();

        $table->timestamp('disbursed_at')
            ->nullable()
            ->after('approved_by');

        $table->foreignId('disbursed_by')
            ->nullable()
            ->after('disbursed_at')
            ->constrained('users')
            ->nullOnDelete();

        $table->timestamp('completed_at')
            ->nullable()
            ->after('disbursed_by');

       });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    Schema::table('loans', function (Blueprint $table) {

        $table->dropForeign(['approved_by']);
        $table->dropForeign(['disbursed_by']);

        $table->dropColumn([

            'application_status',
            'system_recommendation',

            'loan_status',

            'principal_amount',
            'interest_amount',

            'total_repaid',
            'outstanding_balance',

            'approved_at',
            'approved_by',

            'disbursed_at',
            'disbursed_by',

            'completed_at',
        ]);
    });
  }
};
