<?php


namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Api\ApiResponseException;
use App\Http\Controllers\Controller;
use App\Models\User;
use EasyWeChat\Factory;
use EasyWeChat\Kernel\Exceptions\InvalidConfigException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\JWTAuth;

class AuthController extends Controller
{

    public function login()
    {
        return view('login');
    }

    public function webLogin(Request $request)
    {
        $credentials = $request->only(['username', 'password']);
        $guard = $request->post('guard', 'web');
        if (Auth::guard($guard)->attempt($credentials)) {
            return success(Auth::user(), '登录成功');
        } else {
            return failure(10001);
        }
    }

    public function apiLogin(Request $request)
    {
        $credentials = $request->only('username', 'password');
        if ($token = Auth::guard('api')->attempt($credentials)) {
            return success([
                'token'         => $token,
                'Authorization' => "Bearer $token",
                'userinfo'      => Auth::guard('api')->user(),
            ], '登录成功');
        } else {
            return failure(10001);
        }
    }

    public function doRegister(Request $request)
    {
        try {
            $data = $request->only(['username', 'password', 'email']);

            //验证
            $validate = validator($data, [
                'username' => 'required|unique:users|string',
                'password' => 'required|string|max:25|min:6',
                'email'    => "required|email",
            ], [
                'username.required' => '用户名必填',
                'username.unique'   => "已存在用户名:{$data['username']}",
                'username.string'   => '用户名格式错误',
                'password.required' => '密码必填',
                'password.string'   => '密码格式错误',
                'password.max'      => '密码长度最大为25',
                'password.min'      => '密码长度最小为6',
                'email.required'    => '邮箱必填',
                'email.email'       => '邮箱格式错误',
            ]);

            if ($validate->fails()) {
                return failure(400, $validate->errors()->first());
            }

            $user = new User();

            $user->username = $data['username'];
            $user->password = Hash::make($data['password']);
            $user->email = $data['email'];
            $user->uuid = uuid();

            if ($user->save()) {
                return success($user, '注册成功');
            } else {
                return failure(0, '注册失败');
            }

        } catch (\Exception $e) {
            return failure(500, '', $e);
        }
    }

    public function userinfo(){
        return success(auth()->user());
    }
}
