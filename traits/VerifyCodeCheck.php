<?php namespace addons\wefeemall\traits;

use think\captcha\Captcha;

trait VerifyCodeCheck
{

    protected function checkVerifyCode()
    {
        $captcha = new Captcha();
        if (! $captcha->check(request()->param('verify_code'))) {
            $this->error('验证码错误');
        }
    }

}