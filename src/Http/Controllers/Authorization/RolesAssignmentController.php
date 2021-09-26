<?php

namespace Sinarajabpour1998\LaraCore\Http\Controllers\Authorization;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;

class RolesAssignmentController
{
    protected $rolesModel;
    protected $permissionModel;
    protected $assignPermissions;

    public function __construct()
    {
        $this->rolesModel = Config::get('laratrust.models.role');
        $this->permissionModel = Config::get('laratrust.models.permission');
        $this->assignPermissions = Config::get('laratrust.panel.assign_permissions_to_user');
    }

    public function index(Request $request)
    {
        /*
        $modelKey = $request->get('model');
        $userModel = Config::get('laratrust.user_models')[$modelKey] ?? null;
        $userModel::query()->withCount(['roles', 'permissions'])>paginate();
        */
        $userModel = Config::get('laratrust.user_models')['users'] ?? null;
        $roleModel = Config::get('laratrust.models')['role'] ?? null;
        $roles = $roleModel::query()->get();
        $users = $userModel::query();
        $show_filter = 'false';
        if ($request->has('full_name') && $request->full_name != ''){
            $users = $users->whereRaw("concat_ws(' ', first_name, last_name) like ?", ['%'. $request->full_name . '%']);
            $show_filter = 'true';
        }
        if ($request->has('email') && $request->email != ''){
            $users = $users->whereRaw("email like ?", ['%'. $request->email . '%']);
            $show_filter = 'true';
        }
        if ($request->has('mobile') && $request->mobile != ''){
            $users = $users->whereRaw("mobile like ?", ['%'. $request->mobile . '%']);
            $show_filter = 'true';
        }
        if ($request->has('role') && $request->role != ''){
            $users = $users->whereRoleIs($request->role);
            $show_filter = 'true';
        }
        $users = $users->paginate();
        return View::make('vendor.LaraCore.authorization.roles-assignment.index', [
            'models' => array_keys(Config::get('laratrust.user_models')),
            'modelKey' => 'users',
            'users' => $users,
            'show_filter' => $show_filter,
            'roles' => $roles
        ]);
    }

    public function edit(Request $request, $modelId)
    {
        $modelKey = $request->get('model');
        $userModel = Config::get('laratrust.user_models')[$modelKey] ?? null;

        if (!$userModel) {
            Session::flash('laratrust-error', 'Model was not specified in the request');
            return redirect(route('roles-assignment.index'));
        }

        $user = $userModel::query()
            ->with(['roles:id,name,display_name', 'permissions:id,name,display_name'])
            ->findOrFail($modelId);

        $roles = $this->rolesModel::all(['id', 'name','display_name'])
            ->map(function ($role) use ($user) {
                $role->assigned = $user->roles
                    ->pluck('id')
                    ->contains($role->id);

                return $role;
            });
        if ($this->assignPermissions) {
            $permissions = $this->permissionModel::all(['id', 'name','display_name'])
                ->map(function ($permission) use ($user) {
                    $permission->assigned = $user->permissions
                        ->pluck('id')
                        ->contains($permission->id);

                    return $permission;
                });
        }


        return View::make('vendor.LaraCore.authorization.roles-assignment.edit', [
            'modelKey' => $modelKey,
            'roles' => $roles,
            'permissions' => $this->assignPermissions ? $permissions : null,
            'user' => $user,
        ]);
    }

    public function update(Request $request, $modelId)
    {
        $modelKey = $request->get('model');
        $userModel = Config::get('laratrust.user_models')[$modelKey] ?? null;

        if (!$userModel) {
            Session::flash('laratrust-error', 'Model was not specified in the request');
            return redirect()->back();
        }

        $user = $userModel::findOrFail($modelId);
        $user->syncRoles($request->get('roles') ?? []);
        if ($this->assignPermissions) {
            $user->syncPermissions($request->get('permissions') ?? []);
        }
        Session()->flash('success', 'کاربر باموفقیت بروزرسانی شد.');
        return redirect()->back();
    }
}
