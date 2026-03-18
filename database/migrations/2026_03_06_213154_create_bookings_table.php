<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {

            $table->id();

            $table->foreignId('branch_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('client_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('trip_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->integer('seat_number')->nullable();

            $table->decimal('purchase_price',14,2)->default(0);

            $table->decimal('sale_price',14,2)->default(0);

            $table->decimal('discount_percent',5,2)->default(0);

            $table->decimal('discount_amount',14,2)->default(0);

            $table->decimal('total_before_discount',14,2)->default(0);

            $table->decimal('final_price',14,2)->default(0);

            $table->foreignId('currency_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('status')->default('pending');

            $table->foreignId('created_by')
                ->constrained('employees');

            $table->timestamps();

            $table->unique(['trip_id','seat_number']);

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
