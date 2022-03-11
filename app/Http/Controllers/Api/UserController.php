<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController as Controller;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Guard;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:sanctum')->except('login', 'register');
    }

    public function login(UserRequest $request)
    {
        if (Auth::attempt(['name' => $request->name, 'password' => $request->password])) {
            $user = Auth::user();
            $token = $user->createToken('auth_token')->plainTextToken;
            return $this->success(['token' => $token]);
        }

//        if (Auth::attempt(['email' => $request->name, 'password' => $request->password])) {
//            $user = Auth::user();
//            return $this->success(['token' => $user->createToken('api_auth')->plainTextToken]);
//        }
        return $this->failed('登录失败!', 403);

    }

    public function register(UserRequest $request)
    {
        $user = User::query()->create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);
        if ($user) {
            $token = $user->createToken('auth_token')->plainTextToken;
            return $this->success($token);
        }
        return $this->failed('注册失败', 500);

    }

    public function info()
    {
        $user = Auth::guard('sanctum')->user();
//        $user = Auth::guard('sanctum')->user();
        return $this->success(UserResource::make($user));
    }

    public function logout()
    {
        $user = request()->user();
        $user->tokens()->where('id', $user->currentAccessToken()->id)->delete();
        return $this->success('退出成功!');

    }

    public function index()
    {

    }

    public function assignRole(Request $request)
    {
        $user = Auth::user();
        $roleName = $request->name;
        $roleId = $request->id;
        if ($user->hasRole($roleName)) {
            $user->removeRole($roleName);
            return $this->message('成功!');
        }
        if ($roleId) {
            $user->assignRole($roleId);
            return $this->message('成功!');
        }
        if ($roleName) {
            $user->assignRole($roleName);
            return $this->message('成功');
        }
        return $this->failed('分配失败');
    }
}
