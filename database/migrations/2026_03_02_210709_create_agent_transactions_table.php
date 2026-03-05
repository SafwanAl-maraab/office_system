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
    Schema::create('agent_transactions', function (Blueprint $table) {
        $table->id();

        $table->foreignId('agent_id')
              ->constrained('agents')
              ->cascadeOnDelete();

        $table->foreignId('branch_id')
              ->constrained('branches')
              ->cascadeOnDelete();

        $table->foreignId('visa_id')
              ->nullable()
              ->constrained('visas')
              ->nullOnDelete();

        $table->foreignId('agent_payment_id')
              ->nullable()
              ->constrained('agent_payments')
              ->nullOnDelete();

        $table->enum('type', [
            'visa_cost',
            'payment',
            'adjustment'
        ]);

        $table->decimal('amount', 14, 2);

        $table->foreignId('currency_id')
              ->constrained('currencies');

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agent_transactions');
    }
};
