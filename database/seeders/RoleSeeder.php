<?php

namespace Database\Seeders;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */


    public function run(): void
    {
        Role::create([
            'name' => 'موظف مبيعات',
            'max_discount_percentage' => 5
        ]);

        Role::create([
            'name' => 'مدير فرع',
            'max_discount_percentage' => 15
        ]);

        Role::create([
            'name' => 'مدير عام',
            'max_discount_percentage' => 30
        ]);
    }
}
