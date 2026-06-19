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
        Schema::create('cashbox_exchanges', function (Blueprint $table) {

            $table->id();

            $table->foreignId('branch_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('from_currency_id')
                ->constrained('currencies')
                ->cascadeOnDelete();

            $table->foreignId('to_currency_id')
                ->constrained('currencies')
                ->cascadeOnDelete();

            $table->decimal(
                'from_amount',
                18,
                2
            );

            $table->decimal(
                'rate',
                18,
                6
            );

            $table->decimal(
                'to_amount',
                18,
                2
            );

            $table->text('notes')
                ->nullable();

            $table->foreignId('created_by')
                ->constrained('employees')
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | عكس العملية
            |--------------------------------------------------------------------------
            */

            $table->boolean('is_reversed')
                ->default(false);

            $table->timestamp('reversed_at')
                ->nullable();

            $table->foreignId('reversed_by')
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
        Schema::dropIfExists('cashbox_exchanges');
    }
};
