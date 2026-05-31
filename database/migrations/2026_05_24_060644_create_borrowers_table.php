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
        Schema::create('borrowers', function (Blueprint $table) {
                $table->id();
                $table->string('firstname');
                $table->string('middlename')->nullable();
                $table->string('lastname');
                $table->string('contact_no');
                $table->text('address');
                $table->string('email')->nullable();
                $table->string('tax_id')->nullable();
                $table->timestamps();
	});

	Schema::table('borrowers', function (Blueprint $table) {
    $table->string('address')->nullable()->change();
	});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('borrowers');
    }
};

