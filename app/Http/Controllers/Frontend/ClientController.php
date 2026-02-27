<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Models\Client;

class ClientController extends Controller
{
    /**
     * عرض العملاء
     */
    public function index(Request $request)
    {
        $branchId = $this->getBranchId();

        $query = Client::where('branch_id', $branchId);


        // البحث
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('passport_number', 'like', "%{$search}%")
                    ->orWhere('national_id', 'like', "%{$search}%");
            });
        }

        $clients = $query->latest()->paginate(12);

        return view('frontend.clients.index', compact('clients'));
    }

    /**
     * تخزين عميل جديد
     */
    public function store(Request $request)
    {
        $branchId = $this->getBranchId();

        $data = $request->validate([
            'full_name'       => 'required|string|max:255',
            'phone'           => 'required|string|max:50',
            'passport_number' => 'nullable|string|max:100',
            'national_id'     => 'nullable|string|max:100',
            'address'         => 'nullable|string|max:255',
            'notes'           => 'nullable|string',
            'status'          => 'required|boolean',
        ]);

        $data['branch_id'] = $branchId;

        Client::create($data);

        return redirect()
            ->route('clients.index')
            ->with('success', 'تم إضافة العميل بنجاح');
    }

    /**
     * تحديث عميل
     */
    public function update(Request $request, $id)
    {
        $branchId = $this->getBranchId();

        $client = Client::where('id', $id)
            ->where('branch_id', $branchId)
            ->firstOrFail();

        $data = $request->validate([
            'full_name'       => 'required|string|max:255',
            'phone'           => 'required|string|max:50',
            'passport_number' => 'nullable|string|max:100',
            'national_id'     => 'nullable|string|max:100',
            'address'         => 'nullable|string|max:255',
            'notes'           => 'nullable|string',
            'status'          => 'required|boolean',
        ]);

        $client->update($data);

        return redirect()
            ->route('clients.index')
            ->with('success', 'تم تحديث بيانات العميل');
    }

    /**
     * حذف عميل
     */
    public function destroy($id)
    {
        $branchId = $this->getBranchId();

        $client = Client::where('id', $id)
            ->where('branch_id', $branchId)
            ->firstOrFail();

        // منع الحذف إذا لديه تأشيرات
        if ($client->visas()->exists()) {
            return redirect()
                ->route('clients.index')
                ->with('error', 'لا يمكن حذف العميل لوجود عمليات مرتبطة به');
        }

        $client->delete();

        return redirect()
            ->route('clients.index')
            ->with('success', 'تم حذف العميل بنجاح');
    }

    /**
     * جلب رقم الفرع من المستخدم المسجل
     */
    private function getBranchId()
    {
        $user = auth()->user();

        if (!$user || !$user->employee || !$user->employee->branch) {
            abort(403, 'المستخدم غير مرتبط بفرع');
        }

        return $user->employee->branch->id;
    }
}
