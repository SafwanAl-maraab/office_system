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
        Schema::create('exchange_rates', function (Blueprint $table) {

            $table->id();

            $table->foreignId('branch_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('from_currency_id')
                ->constrained('currencies');

            $table->foreignId('to_currency_id')
                ->constrained('currencies');

            $table->date('rate_date');

            $table->decimal(
                'rate',
                18,
                6
            );

            $table->boolean('is_default')
                ->default(false);

            $table->foreignId('created_by')
                ->constrained('employees');



            $table->timestamps();

            $table->unique([
                'branch_id',
                'from_currency_id',
                'to_currency_id'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exchange_rates');
    }
};
