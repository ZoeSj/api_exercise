<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\UserRequest;
use App\Models\User;
use App\Transformers\UserTransformer;

class UsersController extends Controller
{
    /**
     * @Name 用户中心-注册
     * @Description 用户注册
     * @Param name:登录邮箱 @ParamTest zhangwei0933@126.com
     * @Param password:登录密码 @ParamTest 12345678
     *
     * @Response 通用格式:{"code":响应码,"message":"错误描述","data":{}}
     * data{
     *    name:"用户名",
     *    phone: "手机号码",
     *    password: "密码",
     * }
     */

    public function store(UserRequest $request)
    {
        $verifyData = \Cache::get($request->verification_key);
        if (!$verifyData) {
            return $this->response->error('验证码已失效', 422);
        }

        //hash_equals 是可防止时序攻击的字符串比较
        if (!hash_equals($verifyData['code'], $request->verification_code)) {
            //return 401
            return $this->response->errorUnauthorized('验证码错误');
        }

        $user = User::create([
            'name' => $request->name,
            'phone' => $verifyData['phone'],
            'password' => bcrypt($request->password),
        ]);
        log::info('验证码信息',$verifyData);
        //清除验证码缓存
        \Cache::forget($request->verification_key);
        return $this->response->item($user, new UserTransformer())
        ->setMeta([
            'access_token' => \Auth::guard('api')->fromUser($user),
            'token_type' => 'Bearer',
            'expires_in' => \Auth::guard('api')->factory()->getTTL() * 60
        ])
        ->setStatusCode(201);
    }

    public function me()
    {
        return $this->response->item($this->user(), new UserTransformer());
    }
}
