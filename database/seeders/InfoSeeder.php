<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Info;
use App\Models\Branch;

class InfoSeeder extends Seeder
{
    public function run(): void
    {
        $branch = Branch::first();

        Info::create([
            'branch_id' => $branch->id,
            'office_name' => 'مكتب النخبة للسفريات والسياحة',
            'logo' => null,
            'primary_phone' => '777123456',
            'secondary_phone' => '01-234567',
            'email' => 'info@office.com',
            'address' => 'صنعاء - شارع الزبيري',
            'short_description' => 'نقدم خدمات التأشيرات وقطع الجوازات بأفضل جودة وسرعة.',
            'facebook' => null,
            'whatsapp' => '777123456',
            'website' => null,
        ]);
    }
}
