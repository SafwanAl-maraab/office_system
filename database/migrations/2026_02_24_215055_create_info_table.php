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
        Schema::create('infos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('branch_id')
                ->unique()
                ->constrained()
                ->cascadeOnDelete();

            $table->string('office_name');
            $table->string('logo')->nullable();

            $table->string('primary_phone')->nullable();
            $table->string('secondary_phone')->nullable();

            $table->string('email')->nullable();
            $table->string('address')->nullable();

            $table->text('short_description')->nullable();

            $table->string('facebook')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('website')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('info');
    }
};
