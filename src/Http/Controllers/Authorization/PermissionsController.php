<?php

namespace Sinarajabpour1998\LaraCore\Http\Controllers\Authorization;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;

class PermissionsController
{
    protected $permissionModel;

    public function __construct()
    {
        $this->permissionModel = Config::get('laratrust.models.permission');
    }

    public function index(Request $request)
    {
        $show_filter = false;
        $permissions = $this->permissionModel::query();
        if ($request->has('name') && $request->name != ''){
            $permissions = $permissions->whereRaw("name like ?", ['%'. $request->name .'%']);
            $show_filter = 'true';
        }
        if ($request->has('display_name') && $request->display_name != ''){
            $permissions = $permissions->whereRaw("display_name like ?", ['%'. $request->display_name .'%']);
            $show_filter = 'true';
        }
        $permissions = $permissions->paginate(10);
        return View::make('vendor.LaraCore.authorization.permissions.index', [
            'permissions' => $permissions,
            'show_filter' => $show_filter
        ]);
    }

    public function create()
    {
        return View::make('vendor.LaraCore.authorization.edit', [
            'model' => null,
            'type' => 'permission',
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|username|unique:permissions,name',
            'display_name' => 'nullable|string',
            'description' => 'nullable|string',
        ],[
            'name.username' => 'فیلد نام میتواند شامل حروف انگلیسی، اعداد و ـ می تواند باشد'
        ]);

        $this->permissionModel::create($data);

        Session::flash('laratrust-success', 'Permission created successfully');
        return redirect(route('permissions.index'));
    }

    public function edit($id)
    {
        $permission = $this->permissionModel::findOrFail($id);

        return View::make('vendor.LaraCore.authorization.edit', [
            'model' => $permission,
            'type' => 'permission',
        ]);
    }

    public function update(Request $request, $id)
    {
        $permission = $this->permissionModel::findOrFail($id);

        $data = $request->validate([
            'display_name' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        $permission->update($data);

        Session::flash('laratrust-success', 'Permission updated successfully');
        return redirect(route('permissions.index'));
    }

    public function destroy($id)
    {
        $usersAssignedToPermission = DB::table(Config::get('laratrust.tables.permission_user'))
            ->where(Config::get('laratrust.foreign_keys.permission'), $id)
            ->count();

        if ($usersAssignedToPermission > 0) {
            Session::flash('laratrust-warning', 'Permission is attached to one or more users. It can not be deleted');
        } else {
            Session::flash('laratrust-success', 'Permission deleted successfully');
            $this->permissionModel::destroy($id);
        }

        return response()->json(['status' => 'با موفقیت حذف شد']);
    }
}
