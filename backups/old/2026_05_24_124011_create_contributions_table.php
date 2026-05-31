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

                $table->decimal('amount', 12, 2);

                $table->date('contribution_date');

                $table->string('type')->default('monthly');
        // monthly, fine, extra, etc.

                $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contributions');
    }
};
