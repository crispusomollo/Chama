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
        Schema::table('repayments', function (Blueprint $table) {

            /*
            |------------------------------------------------------------------
            | PAYMENT REFERENCE
            |------------------------------------------------------------------
            */

            $table->string('reference_number')
                ->nullable()
                ->unique()
                ->after('loan_id');

            /*
            |------------------------------------------------------------------
            | PAYMENT CHANNEL
            |------------------------------------------------------------------
            */

            $table->string('payment_method')
                ->default('cash')
                ->after('amount');

            /*
            |------------------------------------------------------------------
            | PAYMENT STATUS
            |------------------------------------------------------------------
            */

            $table->string('status')
                ->default('completed')
                ->after('payment_method');

            /*
            |------------------------------------------------------------------
            | OPTIONAL RECEIVED BY
            |------------------------------------------------------------------
            */

            $table->unsignedBigInteger('received_by')
                ->nullable()
                ->after('notes');

        });
    }

    /**
     * Reverse migrations.
     */
    public function down(): void
    {
        Schema::table('repayments', function (Blueprint $table) {

            $table->dropColumn([

                'reference_number',
                'payment_method',
                'status',
                'received_by',

            ]);

        });
    }
};
