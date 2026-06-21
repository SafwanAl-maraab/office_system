<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Employee;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $employees = Employee::take(4)->get();

        if ($employees->count() < 4) {
            return;
        }

        // مدير عام 1
        $user1 = User::create([
            'employee_id' => $employees[0]->id,
            'name' => 'Super Admin',
            'email' => 'admin@office.com',
            'password' => Hash::make('12345678'),
        ]);

        $user1->assignRole('مدير عام');

        // مدير عام 2
        $user2 = User::create([
            'employee_id' => $employees[1]->id,
            'name' => 'Owner',
            'email' => 'owner@office.com',
            'password' => Hash::make('12345678'),
        ]);

        $user2->assignRole('مدير عام');

        // مدير فرع عمران
        $user3 = User::create([
            'employee_id' => $employees[2]->id,
            'name' => 'Branch Manager',
            'email' => 'branch@office.com',
            'password' => Hash::make('12345678'),
        ]);

        $user3->assignRole('مدير فرع');

        // محاسب
        $user4 = User::create([
            'employee_id' => $employees[3]->id,
            'name' => 'Accountant',
            'email' => 'accountant@office.com',
            'password' => Hash::make('12345678'),
        ]);

        $user4->assignRole('محاسب');
    }
}
