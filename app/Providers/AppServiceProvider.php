<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Info;
use App\Models\Branch;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
class AppServiceProvider extends ServiceProvider
{

    public function boot(): void
    {
        // مشاركة البيانات تلقائياً مع الهيدر واللايوت
        View::composer(['frontend.dashboard.partials.hero' ,'frontend.layouts.header'], function ($view) {
            $info = Info::first();
            $branchName = null;

            // إذا كان هناك مستخدم مسجل دخول، نجلب اسم الفرع الخاص به
            if (Auth::check() && Auth::user()->employee) {
                $branchId = Auth::user()->employee->branch_id;
                $branchName = Branch::where('id', $branchId)->value('name');
            }

            $view->with([
                'info' => $info,
                'branchName' => $branchName
            ]);
        });
    }
}
