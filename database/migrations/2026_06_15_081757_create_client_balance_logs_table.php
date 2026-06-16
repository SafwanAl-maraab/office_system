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
        Schema::create('client_balance_logs', function (Blueprint $table) {

            $table->id();

            $table->foreignId('client_id');

            $table->foreignId('currency_id');

            $table->decimal('amount',14,2);

            $table->enum('type', [

                'receipt',
                'payment',
                'settlement',
                'exchange_out',
                'exchange_in',
                'opening_balance',
                'refund',
                //'refund_settlement'

            ]);

            $table->string('reference_type')->nullable();

            $table->unsignedBigInteger('reference_id')->nullable();

            $table->text('notes')->nullable();

            $table->foreignId('created_by');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_balance_logs');
    }
};
