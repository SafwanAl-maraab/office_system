<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('voucher_allocations', function (Blueprint $table) {

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

            $table->decimal('amount',12,2);

            $table->text('notes')->nullable();

            $table->foreignId('created_by')
                ->constrained('employees');

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('voucher_allocations');
    }
};
