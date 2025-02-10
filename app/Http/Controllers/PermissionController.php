<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Traits\Error;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PermissionController extends Controller
{
    use Error;

    protected $systemPermissions = [
        'claims-all',
        'claims-view',
        'claims-create',
        'claims-edit',
        'claims-delete',
        'users-all',
        'users-view',
        'users-create',
        'users-edit',
        'users-delete',
        'roles-all',
        'roles-view',
        'roles-create',
        'roles-edit',
        'roles-delete',
        'permissions-all',
        'permissions-view',
        'permissions-create',
        'permissions-edit',
        'permissions-delete',
        'stats-all',
        'stats-view'
    ];

    public function index(Request $request)
    {
        $permissions = Permission::get();
        return response()->json($permissions);
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => ['required', 'unique:permissions,name'],
            ]);

            $permission = new Permission();
            foreach ($validated as $key => $val) {
                $permission->{$key} = $val;
            }
            $permission->save();

            $adminRole = Role::where('name', 'admin')->first();
            $adminRole->permissions()->attach([$permission->id]);

            return response()->json($permission);
        } catch (\Exception $error) {
            return $this->errorResponse($error);
        }
    }

    public function update(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => [
                    'required',
                    Rule::unique('permissions')->ignore($request->permissionId),
                ],
            ]);

            $permission = Permission::find($request->permissionId);
            if (!$permission) {
                throw new \Exception('Error|Permission not found--404', 13333);
            }

            if (in_array($permission->name, $this->systemPermissions)) {
                throw new \Exception(
                    'Error|System permissions can\'t be edited--403',
                    13333
                );
            }

            foreach ($validated as $key => $val) {
                $permission->{$key} = $val;
            }
            $permission->save();

            return response()->json($permission);
        } catch (\Exception $error) {
            return $this->errorResponse($error);
        }
    }

    public function destroy(Request $request)
    {
        try {
            $permission = Permission::find($request->permissionId);
            if (!$permission) {
                throw new \Exception('Error|Permission not found--404', 13333);
            }

            if (in_array($permission->name, $this->systemPermissions)) {
                throw new \Exception(
                    'Error|System permissions can\'t be deleted--403',
                    13333
                );
            }

            $permission->delete();

            return response()->json([], 204);
        } catch (\Exception $error) {
            return $this->errorResponse($error);
        }
    }
}
