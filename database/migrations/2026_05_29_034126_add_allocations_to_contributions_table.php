<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contributions', function (Blueprint $table) {

            $table->decimal('shares_amount', 12, 2)
                ->default(0)
                ->after('amount');

            $table->decimal('insurance_amount', 12, 2)
                ->default(0)
                ->after('shares_amount');

        });
    }

    public function down(): void
    {
        Schema::table('contributions', function (Blueprint $table) {

            $table->dropColumn([
                'shares_amount',
                'insurance_amount'
            ]);

        });
    }
};
