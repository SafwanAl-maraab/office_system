<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;
use App\Models\Branch;
use App\Models\Role;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        $branch = Branch::first();
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
