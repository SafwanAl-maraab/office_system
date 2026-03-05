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
        Schema::create('trip_group_buses', function (Blueprint $table) {
            $table->id();

            // مجموعة الرحلة
            $table->foreignId('trip_group_id')
                ->constrained()
                ->cascadeOnDelete();

            // الباص
            $table->foreignId('bus_id')
                ->constrained()
                ->cascadeOnDelete();

            // السائق
            $table->foreignId('driver_id')
                ->constrained()
                ->cascadeOnDelete();


            // ملاحظات
            $table->text('notes')->nullable();

            $table->timestamp('created_at')->useCurrent();

            // منع تكرار نفس الباص داخل نفس المجموعة
            $table->unique(['trip_group_id', 'bus_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trip_group_buses');
    }
};
