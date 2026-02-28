<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up(): void
{
Schema::create('request_types', function (Blueprint $table) {
$table->id();

$table->foreignId('branch_id')
->constrained('branches')
->cascadeOnDelete();

$table->string('name'); // جواز عادي / مستعجل / بطاقة

$table->enum('service_category', ['passport', 'card']);

$table->decimal('price', 14, 2);

$table->foreignId('currency_id')
->constrained('currencies')
->restrictOnDelete();

$table->boolean('status')->default(true);

$table->timestamps();
});
}

public function down(): void
{
Schema::dropIfExists('request_types');
}
};
