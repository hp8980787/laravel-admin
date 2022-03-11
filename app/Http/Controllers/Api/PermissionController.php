<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as Controller;
use App\Http\Requests\Api\PermissionRequest;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function index()
    {
        $permissions = Permission::query()->get();
        return $this->success($permissions);
    }

    public function store(PermissionRequest $request)
    {
        $name = $request->name;
        $permission = Permission::query()->create([
            'name' => $name
        ]);
        return $this->success($permission);
    }

    public function update(PermissionRequest $request)
    {
        $id = $request->id;
        $permission = Permission::query()->findOrFail($id);
        $permission->name = $request->name;
        $permission->save();
        return $this->message('修改成功!');
    }

    public function destroy($id)
    {
        Permission::query()->where('id',$id)->delete();
        return $this->message('删除成功!');
    }
}
