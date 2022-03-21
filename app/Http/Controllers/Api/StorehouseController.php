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
        $data = Storehouse::query()->get();
        return $this->success($data);
    }

    public function store(StorehouseRequest $request)
    {

    }

    public function update()
    {

    }

    public function destroy($id)
    {
    }
}
