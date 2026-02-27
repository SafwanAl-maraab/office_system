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
        Schema::create('request_documents', function (Blueprint $table) {
            $table->id();

            $table->foreignId('request_id')->constrained()->cascadeOnDelete();

            $table->string('file_path');
            $table->string('file_type')->nullable();
            $table->string('document_type')->nullable();

            $table->foreignId('uploaded_by')->constrained('employees');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_documents');
    }
};
