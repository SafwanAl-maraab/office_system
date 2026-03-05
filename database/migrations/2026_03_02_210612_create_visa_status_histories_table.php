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
    Schema::create('visa_status_histories', function (Blueprint $table) {
        $table->id();

        $table->foreignId('visa_id')
              ->constrained('visas')
              ->cascadeOnDelete();

        $table->string('old_status')->nullable();
        $table->string('new_status');

        $table->foreignId('changed_by')
              ->constrained('employees')
              ->cascadeOnDelete();

        $table->text('notes')->nullable();

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visa_status_histories');
    }
};
