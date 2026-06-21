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
        $branch1 = Branch::first();
        $branch2 = Branch::skip(1)->first();

        $role = Role::first();

        Employee::create([
            'branch_id' => $branch1->id,
            'role_id' => $role->id,
            'full_name' => 'مدير النظام',
            'phone' => '777000111',
            'salary' => 0,
            'commission_percentage' => 0,
            'status' => true,
        ]);

        Employee::create([
            'branch_id' => $branch1->id,
            'role_id' => $role->id,
            'full_name' => 'مالك النظام',
            'phone' => '777000112',
            'salary' => 0,
            'commission_percentage' => 0,
            'status' => true,
        ]);

        Employee::create([
            'branch_id' => $branch2->id,
            'role_id' => $role->id,
            'full_name' => 'مدير فرع عمران',
            'phone' => '777000113',
            'salary' => 0,
            'commission_percentage' => 0,
            'status' => true,
        ]);

        Employee::create([
            'branch_id' => $branch1->id,
            'role_id' => $role->id,
            'full_name' => 'محاسب',
            'phone' => '777000114',
            'salary' => 0,
            'commission_percentage' => 0,
            'status' => true,
        ]);

        Employee::create([
            'branch_id' => $branch1->id,
            'role_id' => $role->id,
            'full_name' => 'موظف تأشيرات',
            'phone' => '777000115',
            'salary' => 0,
            'commission_percentage' => 0,
            'status' => true,
        ]);

    }
}
