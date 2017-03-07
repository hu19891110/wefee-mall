<?php namespace addons\wefeemall\traits;

use addons\wefeemall\lib\AuthManage;

trait LoginCheck
{

    protected function loginCheck()
    {
        if (! AuthManage::check()) {
            $this->error('请登录', mall_url('index.auth/login'));
        }
    }

}