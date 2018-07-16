<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\CaptachaRequest;
use Gregwar\Captcha\CaptchaBuilder;

class CaptachasController extends Controller
{
    public function store(CaptachaRequest $request, CaptchaBuilder $captchaBuilder)
    {
        $key = 'captcha-' . str_random(15);
        $phone = $request->phone;

        //通过build方法，创建出来验证码图片
        $captcha = $captchaBuilder->build();
        $expiredAt = now()->addMinutes(2);
        //使用getPhrase方法获取验证码文本，跟手机号一起存入缓存
        \Cache::put($key, [
            'phone' => $phone,
            'code' => $captcha->getPhrase()
        ], $expiredAt);
        //返回captcha_key,过期时间以及inline方法获取的base64图片验证码。
        $result = [
            'captcha_key' => $key,
            'expired_at' => $expiredAt->toDateTimeString(),
            'captcha_image_content' => $captcha->inline()
        ];
        return $this->response->array($result)->setStatusCode(201);

    }
}
