<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\Info;
use App\Models\Payment;
use App\Models\Visa;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Role;
use App\Models\Request as Request2;

class EmployeeController extends Controller
{
    /**
     * عرض الموظفين
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        if (!$user || !$user->employee || !$user->employee->branch) {
            abort(403, 'المستخدم غير مرتبط بفرع');
        }

        $branchId = $user->employee->branch->id;

        $query = Employee::where('branch_id', $branchId)
            ->with('role');

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%$search%")
                    ->orWhere('phone', 'like', "%$search%");
            });
        }

        $employees = $query->latest()->paginate(12);
        $roles = Role::all();

        $branch = $user->employee->branch;
        $info = Info::where('branch_id', $branch->id)->first();

        return view('frontend.employees.index', compact('employees','roles', 'info'));
    }

    /**
     * تخزين موظف جديد
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        if (!$user || !$user->employee || !$user->employee->branch) {
            abort(403);
        }

        $branchId = $user->employee->branch->id;

        $request->validate([
            'role_id'               => 'required|exists:roles,id',
            'full_name'             => 'required|string|max:255',
            'phone'                 => 'required|string|max:50',
            'salary'                => 'required|numeric|min:0',
            'commission_percentage' => 'nullable|numeric|min:0|max:100',
            'status'                => 'required|boolean',
        ]);

        Employee::create([
            'branch_id'             => $branchId,
            'role_id'               => $request->role_id,
            'full_name'             => $request->full_name,
            'phone'                 => $request->phone,
            'salary'                => $request->salary,
            'commission_percentage' => $request->commission_percentage ?? 0,
            'status'                => $request->status,
        ]);

        return redirect()
            ->route('employees.index')
            ->with('success', 'تم إضافة الموظف بنجاح');
    }

    /**
     * تحديث الموظف
     */
    public function update(Request $request, $id)
    {
        $employee = $this->findEmployeeForBranch($id);

        $request->validate([
            'role_id'               => 'required|exists:roles,id',
            'full_name'             => 'required|string|max:255',
            'phone'                 => 'required|string|max:50',
            'salary'                => 'required|numeric|min:0',
            'commission_percentage' => 'nullable|numeric|min:0|max:100',
            'status'                => 'required|boolean',
        ]);

        $employee->update([
            'role_id'               => $request->role_id,
            'full_name'             => $request->full_name,
            'phone'                 => $request->phone,
            'salary'                => $request->salary,
            'commission_percentage' => $request->commission_percentage ?? 0,
            'status'                => $request->status,
        ]);

        return redirect()
            ->route('employees.index')
            ->with('success', 'تم تحديث بيانات الموظف');
    }

    /**
     * حذف الموظف
     */
    public function destroy($id)
    {
        $employee = $this->findEmployeeForBranch($id);

        $hasOperations =
            Visa::where('created_by', $employee->id)->exists() ||
            Request2::where('received_by', $employee->id)->exists() ||
            Payment::where('created_by', $employee->id)->exists() ||
            Expense::where('created_by', $employee->id)->exists();
        if ($hasOperations) {
            return redirect()
                ->route('employees.index')
                ->with('error', 'لا يمكن حذف الموظف لأنه مرتبط بعمليات مسجلة');
        }

        $employee->delete();

        return redirect()
            ->route('employees.index')
            ->with('success', 'تم حذف الموظف بنجاح');
    }

    /**
     * حماية Multi-Branch
     */
    private function findEmployeeForBranch($id)
    {
        $user = auth()->user();

        if (!$user || !$user->employee || !$user->employee->branch) {
            abort(403);
        }

        $branchId = $user->employee->branch->id;

        $employee = Employee::where('id', $id)
            ->where('branch_id', $branchId)
            ->first();

        if (!$employee) {
            abort(404);
        }

        return $employee;
    }
}
