<?php

namespace Sinarajabpour1998\LaraCore\Http\Controllers\Authorization;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;

class RolesController
{
    protected $rolesModel;
    protected $permissionModel;

    public function __construct()
    {
        $this->rolesModel = Config::get('laratrust.models.role');
        $this->permissionModel = Config::get('laratrust.models.permission');
    }

    public function index(Request $request)
    {
        $show_filter = false;
        $roles = $this->rolesModel::withCount('permissions');
        if ($request->has('name') && $request->name != ''){
            $roles = $roles->whereRaw("name like ?", ['%'. $request->name .'%']);
            $show_filter = 'true';
        }
        if ($request->has('display_name') && $request->display_name != ''){
            $roles = $roles->whereRaw("display_name like ?", ['%'. $request->display_name .'%']);
            $show_filter = 'true';
        }
        $roles = $roles->paginate(10);
        return View::make('vendor.LaraCore.authorization.roles.index', [
            'roles' => $roles,
            'show_filter' => $show_filter
        ]);
    }

    public function create()
    {
        return View::make('vendor.LaraCore.authorization.edit', [
            'model' => null,
            'permissions' => $this->permissionModel::all(['id', 'name', 'display_name']),
            'type' => 'role',
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|username|unique:roles,name',
            'display_name' => 'nullable|string',
            'description' => 'nullable|string',
        ],[
            'name.username' => 'فیلد نام میتواند شامل حروف انگلیسی، اعداد و ـ می تواند باشد'
        ]);

        $role = $this->rolesModel::create($data);
        $role->syncPermissions($request->get('permissions') ?? []);

        Session::flash('laratrust-success', 'Role created successfully');
        return redirect(route('roles.index'));
    }

    public function edit($id)
    {
        $role = $this->rolesModel::query()
            ->with('permissions:id')
            ->findOrFail($id);
        $permissions = $this->permissionModel::all(['id', 'name', 'display_name'])
            ->map(function ($permission) use ($role) {
                $permission->assigned = $role->permissions
                    ->pluck('id')
                    ->contains($permission->id);

                return $permission;
            });

        return View::make('vendor.LaraCore.authorization.edit', [
            'model' => $role,
            'permissions' => $permissions,
            'type' => 'role',
        ]);
    }

    public function update(Request $request, $id)
    {
        $role = $this->rolesModel::findOrFail($id);

        $data = $request->validate([
            'display_name' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        $role->update($data);
        $role->syncPermissions($request->get('permissions') ?? []);

        Session::flash('laratrust-success', 'Role updated successfully');
        return redirect(route('roles.index'));
    }

    public function destroy($id)
    {
        $usersAssignedToRole = DB::table(Config::get('laratrust.tables.role_user'))
            ->where(Config::get('laratrust.foreign_keys.role'), $id)
            ->count();

        if ($usersAssignedToRole > 0) {
            Session::flash('laratrust-warning', 'Role is attached to one or more users. It can not be deleted');
        } else {
            Session::flash('laratrust-success', 'Role deleted successfully');
            $this->rolesModel::destroy($id);
        }

        return response()->json(['status' => 'با موفقیت حذف شد']);
    }
}
