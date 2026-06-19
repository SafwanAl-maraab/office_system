<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('incomes', function (Blueprint $table) {

            $table->id();

            $table->foreignId('branch_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->decimal(
                'amount',
                18,
                2
            );

            $table->foreignId('currency_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->text('description');

            $table->foreignId('created_by')
                ->constrained(
                    'employees'
                )
                ->cascadeOnDelete();

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('incomes');
    }
};
