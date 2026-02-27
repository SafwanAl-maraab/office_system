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
        Schema::create('drivers', function (Blueprint $table) {
            $table->id();
            // الفرع
            $table->foreignId('branch_id')
                ->constrained()
                ->cascadeOnDelete();

            // اسم السائق
            $table->string('name');

            // الهاتف
            $table->string('phone')->nullable();

            // رقم الرخصة
            $table->string('license_number')->unique();

            // الحالة
            $table->enum('status', ['active', 'inactive', 'suspended'])
                ->default('active');

            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drivers');
    }
};
