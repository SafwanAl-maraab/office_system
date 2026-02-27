<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Branch;
use App\Models\BranchCashbox;
use App\Models\Currency;

class BranchSeeder extends Seeder
{
    public function run(): void
    {
        // إنشاء الفرع
        $branch = Branch::create([
            'name' => 'الفرع الرئيسي',
            'location' => 'اليمن',
            'phone' => '777000111',
            'status' => true,
        ]);

        // جلب جميع العملات
        $currencies = Currency::all();

        // إنشاء خزنة لكل عملة
        foreach ($currencies as $currency) {
            BranchCashbox::create([
                'branch_id' => $branch->id,
                'currency_id' => $currency->id,
                'balance' => 0,
            ]);
        }
    }
}
