<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('client_vouchers', function (Blueprint $table) {

            $table->id();

            $table->foreignId('branch_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('client_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('currency_id')
                ->constrained();

            $table->foreignId('created_by')
                ->constrained('employees');

            $table->enum('type',[
                'receipt',
                'payment',
                'opening_balance'

            ]);

            $table->decimal('amount',12,2);

            $table->text('notes')->nullable();

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('client_vouchers');
    }
};
