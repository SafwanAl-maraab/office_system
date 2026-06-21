<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\RolePermissionPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | عرض الأدوار
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $roles = Role::with('permissions')
            ->where('guard_name', 'web')
            ->latest()
            ->get();

        $permissions = Permission::where(
            'guard_name',
            'web'
        )->get();

        $usersCount = \App\Models\User::count();

        return view(
            'frontend.roles.index',
            compact(
                'roles',
                'permissions',
                'usersCount'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | إنشاء دور
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $data = $request->validate([

            'name' => 'required|string|max:255|unique:roles,name',

            'permissions' => 'nullable|array',

        ]);

        DB::transaction(function () use ($data) {

            $role = Role::create([

                'name' => $data['name'],

                'guard_name' => 'web',

            ]);

            $permissions = $data['permissions'] ?? [];

            $role->syncPermissions(
                $permissions
            );

            foreach ($permissions as $permission) {

                RolePermissionPeriod::create([

                    'role_id' => $role->id,

                    'permission' => $permission,

                    'start_at' => now(),

                    'granted_by' => Auth::id(),

                    'reason' => 'إنشاء الدور',

                ]);
            }
        });

        return back()->with(
            'success',
            'تم إنشاء الدور بنجاح'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | تعديل دور
    |--------------------------------------------------------------------------
    */

    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        $oldPermissions = $role
            ->permissions
            ->pluck('name')
            ->toArray();

        $data = $request->validate([

            'name' => 'required|string|max:255',

            'permissions' => 'nullable|array',

        ]);

        DB::transaction(function () use (

            $role,
            $data,
            $oldPermissions

        ) {

            $role->update([

                'name' => $data['name']

            ]);

            $newPermissions =
                $data['permissions'] ?? [];

            $role->syncPermissions(
                $newPermissions
            );

            $addedPermissions = array_diff(
                $newPermissions,
                $oldPermissions
            );

            $removedPermissions = array_diff(
                $oldPermissions,
                $newPermissions
            );

            foreach ($addedPermissions as $permission) {

                RolePermissionPeriod::create([

                    'role_id' => $role->id,

                    'permission' => $permission,

                    'start_at' => now(),

                    'granted_by' => Auth::id(),

                    'reason' => 'إضافة صلاحية',

                ]);
            }

            foreach ($removedPermissions as $permission) {

                RolePermissionPeriod::where(

                    'role_id',
                    $role->id

                )

                    ->where(
                        'permission',
                        $permission
                    )

                    ->whereNull('end_at')

                    ->update([

                        'end_at' => now(),

                        'revoked_by' => Auth::id(),

                        'reason' => 'سحب صلاحية',

                    ]);
            }
        });

        return back()->with(
            'success',
            'تم تحديث الدور بنجاح'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | حذف دور
    |--------------------------------------------------------------------------
    */

    public function destroy($id)
    {
        $role = Role::findOrFail($id);

        DB::transaction(function () use ($role) {

            RolePermissionPeriod::where(
                'role_id',
                $role->id
            )

                ->whereNull('end_at')

                ->update([

                    'end_at' => now(),

                    'revoked_by' => Auth::id(),

                    'reason' => 'حذف الدور',

                ]);

            $role->delete();
        });

        return back()->with(
            'success',
            'تم حذف الدور'
        );
    }
}
