<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController as Controller;
use App\Http\Requests\Api\StorehouseRequest;
use App\Models\Storehouse;
use Illuminate\Http\Request;

class StorehouseController extends Controller
{
    public function index(Request $request)
    {
        $data = Storehouse::query()->with('country')->get();
        return $this->success($data);
    }

    public function store(StorehouseRequest $request)
    {
        $data = $request->only('name', 'country_id', 'attention');
        Storehouse::query()->create($data);
        return $this->message('添加成功!');
    }

    public function update(StorehouseRequest $request, $id)
    {
        $data = $request->only('name', 'country_id', 'attention');
        Storehouse::query()->where('id', $id)->update($data);
        return $this->message('修改成功!');
    }

    public function destroy($id)
    {
        Storehouse::query()->where('id',$id)->delete();
        return $this->message('删除成功!');
    }

}
