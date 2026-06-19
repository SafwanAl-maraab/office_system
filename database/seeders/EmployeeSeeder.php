<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;
use App\Models\Branch;
// 💡 التعديل السحري: استدعاء موديل الـ Role الأصلي الخاص بالمكتبة مباشرة
use Spatie\Permission\Models\Role;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        $branch = Branch::first();

        // الآن سيقرأ من جدول roles الجديد الخاص بالمكتبة بدون أي مشاكل
        $role = Role::first();

        Employee::create([
            'branch_id' => $branch->id,
            'role_id' => $role->id,
            'full_name' => 'مدير النظام',
            'phone' => '777000111',
            'salary' => 0,
            'commission_percentage' => 0,
            'status' => true,
        ]);
    }
}
