<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Info;
use App\Models\Employee;
use App\Models\Branch;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    /**
     * عرض صفحة الإعدادات
     */
    public function index()
    {

        $user = auth()->user();

        if (!$user || !$user->employee || !$user->employee->branch) {
            abort(403, 'لا يوجد فرع مرتبط بالمستخدم');
        }

        $branch = $user->employee->branch;

        $info = Info::where('branch_id', $branch->id)->first();

        return view('frontend.settings.index', compact('info'));
    }

    /**
     * تحديث بيانات الإعدادات
     */
    public function update(Request $request)
    {
        $branch = auth()->user()->employee->branch;

        $request->validate([
            'office_name'       => 'required|string|max:255',
            'email'             => 'nullable|email|max:255',
            'primary_phone'     => 'nullable|string|max:50',
            'secondary_phone'   => 'nullable|string|max:50',
            'address'           => 'nullable|string|max:255',
            'short_description' => 'nullable|string|max:1000',
            'facebook'          => 'nullable|string|max:255',
            'whatsapp'          => 'nullable|string|max:50',
            'website'           => 'nullable|string|max:255',
            'logo'              => 'nullable|image|max:2048', // 2MB
        ]);

        $info = Info::where('branch_id', $branch->id)->first();

        if (!$info) {
            $info = new Info();
            $info->branch_id = $branch->id;
        }

        // رفع الشعار إذا تم اختياره
        if ($request->hasFile('logo')) {

            // حذف القديم إن وجد
            if ($info->logo && Storage::exists($info->logo)) {
                Storage::delete($info->logo);
            }

            $path = $request->file('logo')->store('office_logos', 'public');
            $info->logo = $path;
        }

        $info->office_name       = $request->office_name;
        $info->email             = $request->email;
        $info->primary_phone     = $request->primary_phone;
        $info->secondary_phone   = $request->secondary_phone;
        $info->address           = $request->address;
        $info->short_description = $request->short_description;
        $info->facebook          = $request->facebook;
        $info->whatsapp          = $request->whatsapp;
        $info->website           = $request->website;

        $info->save();

        return redirect()
            ->route('settings.index')
            ->with('success', 'تم تحديث الإعدادات بنجاح');
    }
}
