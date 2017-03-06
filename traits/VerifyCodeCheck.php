<?php namespace addons\wefeemall\traits;

use think\captcha\Captcha AS CaptchaLib;

trait VerifyCodeCheck
{

    protected function checkVerifyCode()
    {
        $captcha = new CaptchaLib();

        if (! $captcha->check(request()->param('verify_code'))) {
            $this->error('验证码错误');
        }
    }

}