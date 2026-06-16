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
        Schema::create('currency_exchange_allocations', function (Blueprint $table) {

            $table->id();

            $table->foreignId('voucher_id')
                ->constrained('client_vouchers')
                ->cascadeOnDelete();

            $table->foreignId('invoice_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('payment_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->foreignId('source_currency_id')
                ->constrained('currencies');

            $table->foreignId('target_currency_id')
                ->constrained('currencies');

            $table->decimal(
                'exchange_rate',
                18,
                6
            );

            $table->decimal(
                'source_amount',
                18,
                2
            );

            $table->decimal(
                'target_amount',
                18,
                2
            );

            $table->text('notes')
                ->nullable();

            $table->foreignId('created_by')
                ->constrained('employees');

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('currency_exchange_allocations');
    }
};
