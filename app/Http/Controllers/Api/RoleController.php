<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController as Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Requests\Api\RoleRequest;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function store(RoleRequest $request)
    {
        $name = $request->name;
        if ($name) {
            $role = Role::query()->create(['name' => $name]);
            return $this->success($role);
        }
        return $this->failed('创建角色失败!');
    }

    public function update(RoleRequest $request)
    {
        $name = $request->name;
        $id = $request->id;
        $role = Role::query()->findOrFail($id);
        $role->name = $name;
        $role->save();
        return $this->message('修改成功', 'success');
    }

    public function index()
    {
        $roles = Role::query()->get();
        return $this->success($roles);
    }

    public function destroy($id)
    {
        Role::query()->where('id', $id)->delete();
        return $this->message('删除成功');
    }

    public function assignPermissions(Request $request)
    {
        $role = Role::query()->findOrFail($request->id);
        $permissionsId = $request->permissions_ids;
        $role->givePermissionTo($permissionsId);
        return $this->success($role);

    }
}
