<?php namespace addons\wefeemall\controller\api;

use think\Controller;
use think\captcha\Captcha AS CaptchaLib;

class Captcha extends Controller
{

    public function index()
    {
        $captcha = new CaptchaLib([
            'length'   => 4,
            'useCurve' => false,
            'reset'    => false,
        ]);

        ob_clean();

        return $captcha->entry();
    }

}