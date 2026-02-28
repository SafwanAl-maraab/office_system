<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('requests', function (Blueprint $table) {

            $table->foreignId('request_type_id')
                ->after('client_id')
                ->constrained('request_types')
                ->restrictOnDelete();

            // يمكن لاحقًا إزالة service_type إن أردت
        });
    }

    public function down(): void
    {
        Schema::table('requests', function (Blueprint $table) {

            $table->dropForeign(['request_type_id']);
            $table->dropColumn('request_type_id');
        });
    }
};
