<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trips', function (Blueprint $table) {

            $table->id();

            $table->foreignId('branch_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('bus_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('from_city');

            $table->string('to_city');

            $table->date('trip_date');

            $table->time('trip_time');

            $table->decimal('purchase_price',14,2)->default(0);

            $table->decimal('sale_price',14,2)->default(0);

            $table->foreignId('currency_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->text('notes')->nullable();

            $table->string('status')->default('scheduled');

            $table->foreignId('created_by')
                ->constrained('employees');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trips');
    }
};
