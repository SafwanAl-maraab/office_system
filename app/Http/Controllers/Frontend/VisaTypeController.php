<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\VisaType;
use Illuminate\Http\Request;

class VisaTypeController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $visaTypes = VisaType::when($search, function ($q) use ($search) {
                $q->where('name','like','%'.$search.'%');
            })
            ->latest()
            ->paginate(12);

        return view('frontend.visa-types.index', compact('visaTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:visa_types,name',
            'category' => 'nullable|string|max:100',
            'requires_package' => 'required|boolean',
            'default_duration_days' => 'nullable|integer|min:1',
            'status' => 'required|boolean',
        ]);

        VisaType::create($request->all());

        return back()->with('success','تم إنشاء نوع التأشيرة بنجاح');
    }

    public function update(Request $request, $id)
    {
        $visaType = VisaType::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:100|unique:visa_types,name,'.$id,
            'category' => 'nullable|string|max:100',
            'requires_package' => 'required|boolean',
            'default_duration_days' => 'nullable|integer|min:1',
            'status' => 'required|boolean',
        ]);

        $visaType->update($request->all());

        return back()->with('success','تم تحديث نوع التأشيرة');
    }

    public function destroy($id)
    {
        $visaType = VisaType::findOrFail($id);

        if ($visaType->visas()->count() > 0) {
            return back()->with('error','لا يمكن حذف نوع مرتبط بتأشيرات');
        }

        $visaType->delete();

        return back()->with('success','تم الحذف بنجاح');
    }
}