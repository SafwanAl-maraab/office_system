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
        Schema::create('cashbox_transactions', function (Blueprint $table) {

            $table->id();

            $table->foreignId('branch_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('currency_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->decimal(
                'amount',
                18,
                2
            );

            $table->enum('type', [

                'opening_balance',

                'deposit',

                'withdraw',

                'exchange_out',

                'exchange_in',

                'expense',

                'income',

                'refund',

                'adjustment'

            ]);

            $table->string(
                'reference_type'
            )->nullable();

            $table->unsignedBigInteger(
                'reference_id'
            )->nullable();

            $table->text('notes')
                ->nullable();

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('employees')
                ->nullOnDelete();

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cashbox_transactions');
    }
};
