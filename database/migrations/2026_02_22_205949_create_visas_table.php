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
        Schema::create('visas', function (Blueprint $table) {
            $table->id();
    $table->string('visa_number')
              ->unique();
            // العلاقات
            $table->foreignId('branch_id')->constrained()->cascadeOnDelete();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();

            $table->foreignId('visa_type_id')->constrained()->cascadeOnDelete();

            $table->foreignId('package_id')->nullable()->constrained('service_packages')->nullOnDelete();
            $table->foreignId('trip_group_id')->nullable()->constrained()->nullOnDelete();


            $table->foreignId('agent_id')
                ->nullable()
                ->constrained('agents')
                ->nullOnDelete();

            // بيانات إضافية
            $table->string('passport_number');
            $table->string('sponsor_name')->nullable();
            $table->string('job_title')->nullable();

            // الأسعار
            $table->decimal('original_price', 12, 2);
            $table->decimal('discount_percentage', 5, 2)->default(0);
            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->decimal('sale_price', 12, 2);
            $table->decimal('cost_price', 12, 2)->default(0);
            $table->decimal('agent_cost', 12, 2)->default(0);

            $table->foreignId('currency_id')->constrained();
//المورد

            // الحالة
            $table->enum('status', ['pending', 'issued', 'cancelled'])->default('pending');
            $table->date('issue_date')->nullable();
            $table->date('expiry_date')->nullable();

            // المستندات
            $table->string('document_file')->nullable();
            $table->string('image_file')->nullable();

            // الإلغاء
            $table->text('cancel_reason')->nullable();

            // من أنشأ
            $table->foreignId('created_by')->constrained('employees');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visas');
    }
};
