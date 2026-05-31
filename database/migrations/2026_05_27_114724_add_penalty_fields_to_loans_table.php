<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('loans', function (Blueprint $table) {

            $table->decimal('penalty_amount', 12, 2)
                ->default(0)
                ->after('balance');

            $table->boolean('penalty_applied')
                ->default(false)
                ->after('penalty_amount');

        });
    }

    public function down(): void
    {
        Schema::table('loans', function (Blueprint $table) {

            $table->dropColumn([

                'penalty_amount',
                'penalty_applied',

            ]);

        });
    }
};
