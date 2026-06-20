<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * عرض المستخدمين
     */
    public function index(Request $request)
    {
        $query = User::with([
            'employee.branch',
            'roles'
        ]);

        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");

            });
        }

        $users = $query->latest()->paginate(15);

        $employees = Employee::whereDoesntHave('user')
            ->where('status', 1)
            ->with('branch')
            ->get();

        $roles = Role::orderBy('name')->get();

        
$totalUsers = User::count();

$totalAdmins = User::role('مدير عام')->count();

$totalManagers = User::role('مدير فرع')->count();

$totalEmployees = User::count();
        return view(
    'frontend.users.index',
    compact(
        'users',
        'employees',
        'roles',
        'totalUsers',
        'totalAdmins',
        'totalManagers',
        'totalEmployees'
    )
);    }

    /**
     * إنشاء مستخدم
     */
    public function store(Request $request)
    {
        $data = $request->validate([

            'employee_id' => [
                'required',
                'exists:employees,id',
                'unique:users,employee_id'
            ],

            'name' => [
                'required',
                'string',
                'max:255'
            ],

            'email' => [
                'required',
                'email',
                'unique:users,email'
            ],

            'password' => [
                'required',
                'confirmed',

                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
            ],

            'role' => [
                'required',
                'exists:roles,name'
            ]
        ]);

        $user = User::create([

            'employee_id' => $data['employee_id'],

            'name' => $data['name'],

            'email' => $data['email'],

            'password' => Hash::make(
                $data['password']
            )
        ]);

        $user->assignRole(
            $data['role']
        );

        return redirect()
            ->route('users.index')
            ->with(
                'success',
                'تم إنشاء المستخدم بنجاح'
            );
    }

    /**
     * تحديث مستخدم
     */
    public function update(Request $request, User $user)
    {
        $data = $request->validate([

            'name' => 'required|string|max:255',

            'email' => 'required|email|unique:users,email,' . $user->id,

            'role' => 'required|exists:roles,name',

            'password' => [
                'nullable',
                'confirmed',

                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
            ]
        ]);

        $user->update([

            'name' => $data['name'],

            'email' => $data['email']
        ]);

        if (!empty($data['password'])) {

            $user->update([

                'password' => Hash::make(
                    $data['password']
                )
            ]);
        }

        $user->syncRoles([
            $data['role']
        ]);

        return redirect()
            ->route('users.index')
            ->with(
                'success',
                'تم تحديث المستخدم بنجاح'
            );
    }

    /**
     * حذف مستخدم
     */
    public function destroy(User $user)
    {
        if ($user->id == auth()->id()) {

            return back()->with(
                'error',
                'لا يمكن حذف المستخدم الحالي'
            );
        }

        $user->delete();

        return redirect()
            ->route('users.index')
            ->with(
                'success',
                'تم حذف المستخدم بنجاح'
            );
    }
}