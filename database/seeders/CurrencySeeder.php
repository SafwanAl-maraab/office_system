<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Currency;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */


    public function run(): void
    {
        Currency::create([
            'code' => 'YER',
            'name' => 'ريال يمني',
            'symbol' => '﷼',
            'is_default' => true
        ]);

        Currency::create([
            'code' => 'SAR',
            'name' => 'ريال سعودي',
            'symbol' => 'SAR'
        ]);

        Currency::create([
            'code' => 'USD',
            'name' => 'دولار',
            'symbol' => '$'
        ]);
    }
}
