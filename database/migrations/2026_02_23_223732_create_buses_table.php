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
        Schema::create('buses', function (Blueprint $table) {
            $table->id();

            // الفرع المرتبط بالباص
            $table->foreignId('branch_id')
                ->constrained()
                ->cascadeOnDelete();


            $table->foreignId('agent_id')
                ->nullable()
                ->constrained('agents')
                ->nullOnDelete();

            // رقم اللوحة
            $table->string('plate_number')->unique();

            // موديل الباص
            $table->string('model')->nullable();

            // عدد المقاعد
            $table->integer('capacity')->default(0);

            // حالة الباص (active, maintenance, inactive)
            $table->enum('status', ['active', 'maintenance', 'inactive'])
                ->default('active');

            $table->timestamp('created_at')->useCurrent();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buses');
    }
};
