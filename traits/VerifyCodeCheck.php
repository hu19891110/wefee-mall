<?php namespace addons\wefeemall\traits;

trait VerifyCodeCheck
{

    protected function checkVerifyCode()
    {
        if (
            is_null(session('verify_code')) ||
            strtolower(session('verify_code')) !== strtolower(request()->param('verify_code'))
        ) {
            $this->error('验证码错误');
        }
    }

}