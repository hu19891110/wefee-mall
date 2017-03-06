<?php namespace addons\wefeemall\controller\index;

use think\Controller;

class Base extends Controller
{

    public function _initialize()
    {
        parent::_initialize();

        /** 初始化错误，失败界面 */
        config('dispatch_success_tmpl', VIEW_PATH . '/index/common/success.html');
        config('dispatch_error_tmpl', VIEW_PATH . '/index/common/error.html');
    }
}