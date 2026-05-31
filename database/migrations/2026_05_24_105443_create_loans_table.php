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
       Schema::create('loans', function (Blueprint $table) {
         $table->id();

         $table->foreignId('borrower_id')
              ->constrained()
              ->onDelete('cascade');

         $table->decimal('amount', 12, 2);
         $table->decimal('interest_rate', 5, 2)->default(0);
         $table->date('loan_date');
         $table->date('due_date')->nullable();

         $table->string('status')->default('active'); 
         $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
