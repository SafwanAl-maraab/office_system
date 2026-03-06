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

            $table->foreignId('branch_id')->constrained()->cascadeOnDelete();

            $table->foreignId('client_id')->constrained()->cascadeOnDelete();

            $table->foreignId('trip_group_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->foreignId('trip_group_bus_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->string('seat_number')->nullable();

            $table->string('booking_number')->unique();

            $table->decimal('price',12,2);

            $table->foreignId('currency_id')->constrained();

            $table->enum('status',[
                'pending',
                'confirmed',
                'cancelled',
                'completed'
            ])->default('pending');

            $table->text('notes')->nullable();

            $table->foreignId('created_by')
                ->constrained('employees');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};

