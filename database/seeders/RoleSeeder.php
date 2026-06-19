<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. تنظيف الكاش الخاص بالمكتبة لتجنب أي تداخل
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 2. إنشاء الصلاحيات (Permissions) الخاصة بالنظام المالي والخزائن والتقارير
        $permissions = [
            'view-reports',       // عرض التقارير المالية
            'manage-cashboxes',   // إدارة الخزائن والصناديق
            'create-expenses',    // تسجيل مصروفات
            'create-incomes',     // تسجيل إيرادات
            'apply-discounts',    // تطبيق خصومات
            'full-financial-control' // تحكم مالي كامل (تعديل وحذف وإعدادات)
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission, 'web');
        }

        // 3. إنشاء الأدوار (Roles) مع إضافة نسبة الخصم والـ Guard

        // أ. موظف مبيعات
        $salesRole = Role::create([
            'name' => 'موظف مبيعات',
            'guard_name' => 'web',
            'max_discount_percentage' => 5.00
        ]);
        // منح موظف المبيعات صلاحيات محدودة
        $salesRole->givePermissionTo(['create-expenses', 'apply-discounts']);

        // ب. مدير فرع
        $branchManagerRole = Role::create([
            'name' => 'مدير فرع',
            'guard_name' => 'web',
            'max_discount_percentage' => 15.00
        ]);
        // منح مدير الفرع صلاحيات أوسع تشمل التقارير والخزائن للفرع
        $branchManagerRole->givePermissionTo([
            'view-reports',
            'manage-cashboxes',
            'create-expenses',
            'create-incomes',
            'apply-discounts'
        ]);

        // ج. مدير عام
        $generalManagerRole = Role::create([
            'name' => 'مدير عام',
            'guard_name' => 'web',
            'max_discount_percentage' => 30.00
        ]);
        // المدير العام يمتلك كل شيء في النظام
        $generalManagerRole->givePermissionTo(Permission::all());
    }
}
