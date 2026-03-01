<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\RequestType;
use App\Models\Currency;
use Illuminate\Http\Request;

class RequestTypeController extends Controller
{
    public function index()
    {
        $branchId = auth()->user()->employee->branch_id;

        $types = RequestType::with('currency')
            ->where('branch_id', $branchId)
            ->latest()
            ->get();

        $currencies = Currency::where('status', true)->get();

        return view('frontend.request_types.index',
            compact('types', 'currencies'));
    }

    public function store(Request $request)
    {
        $branchId = auth()->user()->employee->branch_id;

        $request->validate([
            'name' => 'required|string|max:255',
            'service_category' => 'required|in:passport,card',
            'price' => 'required|numeric|min:0',
            'currency_id' => 'required|exists:currencies,id'
        ]);

        RequestType::create([
            'branch_id' => $branchId,
            'name' => $request->name,
            'service_category' => $request->service_category,
            'price' => $request->price,
            'currency_id' => $request->currency_id,
            'status' => true,
        ]);

        return back()->with('success', 'تم إضافة النوع بنجاح.');
    }

    public function update(Request $request, $id)
    {
        $branchId = auth()->user()->employee->branch_id;

        $type = RequestType::where('branch_id', $branchId)
            ->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'service_category' => 'required|in:passport,card',
            'price' => 'required|numeric|min:0',
            'currency_id' => 'required|exists:currencies,id'
        ]);

        $type->update([
            'name' => $request->name,
            'service_category' => $request->service_category,
            'price' => $request->price,
            'currency_id' => $request->currency_id,
        ]);

        return back()->with('success', 'تم تحديث النوع.');
    }

    public function destroy($id)
    {
        $branchId = auth()->user()->employee->branch_id;

        $type = RequestType::where('branch_id', $branchId)
            ->findOrFail($id);

        $type->delete();

        return back()->with('success', 'تم حذف النوع.');
    }
}
