<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Employee;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $employee = Employee::first();

        User::create([
            'employee_id' => $employee->id,
            'name' => 'admin',
            'email' => 'admin@office.com',
            'password' => Hash::make('12345678'),
        ]);
    }
}
