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
        Schema::create('role_permission_periods', function (Blueprint $table) {

            $table->id();

            $table->foreignId('role_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('permission');

            $table->timestamp('start_at');

            $table->timestamp('end_at')->nullable();

            $table->foreignId('granted_by')->nullable();

            $table->foreignId('revoked_by')->nullable();

            $table->string('reason')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role_permission_periods');
    }
};
