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
        $branches = [

            [
                'name' => 'الفرع الرئيسي',
                'location' => 'صنعاء',
                'phone' => '777000111',
                'status' => true,
            ],

            [
                'name' => 'فرع عمران',
                'location' => 'عمران',
                'phone' => '777000222',
                'status' => true,
            ],

        ];

        $currencies = Currency::all();

        foreach ($branches as $data) {

            $branch = Branch::create($data);

            foreach ($currencies as $currency) {

                BranchCashbox::create([
                    'branch_id'   => $branch->id,
                    'currency_id' => $currency->id,
                    'balance'     => 0,
                ]);
            }
        }
    }
}
