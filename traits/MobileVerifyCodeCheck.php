<?php namespace addons\wefeemall\traits;

trait MobileVerifyCodeCheck
{

    protected function mobileVerifyCodeCheck()
    {
        $mobile = request()->param('username');
        $mobile_code = request()->param('mobile_verify_code');

        $session = session('mobile_verify_code');

        if (! $session) {
            $this->error('手机验证码错误');
        }

        if ($session['mobile'] != $mobile || $session['code'] != $mobile_code) {
            $this->error('手机验证码错误');
        }
    }

}