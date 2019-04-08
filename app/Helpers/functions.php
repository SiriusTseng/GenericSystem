<?php

if (!function_exists('success')) {
    /**
     * 返回成功结果集
     * @param $data
     * @param string $msg
     * @return \Illuminate\Http\JsonResponse
     */
    function success($data, $msg = "Successful")
    {
        return \App\Helpers\ResultSet::success($data, $msg)->response();
    }
}

if (!function_exists('failure')) {
    /**
     * 返回失败结果集
     * @param int $code
     * @param string $msg
     * @param null $exception
     * @param null $data
     * @return \Illuminate\Http\JsonResponse
     */
    function failure($code = 0, $msg = "Failure", $exception = null, $data = null)
    {
        return \App\Helpers\ResultSet::failure($code, $msg, $exception, $data)->response();
    }
}

if (!function_exists('uuid')) {
    /**
     * 生成uuid
     * @return string
     * @throws Exception
     */
    function uuid()
    {
        return \Webpatser\Uuid\Uuid::generate()->string;
    }
}


if (!function_exists('user')) {
    /**
     * 获取登录的用户
     * @param string $guard
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    function user($guard = 'api')
    {
        return Auth::guard($guard)->user();
    }
}
