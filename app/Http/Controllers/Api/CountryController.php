<?php

namespace App\Http\Controllers\Api;

use App\Models\Country;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as Controller;

class CountryController extends Controller
{
    public function index(Request $request)
    {
        $data = Country::query()->active($request->status)->get();
        return $this->success($data);
    }
}
