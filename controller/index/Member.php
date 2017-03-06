<?php namespace addons\wefeemall\controller\index;

use addons\wefeemall\lib\AuthManage;
use addons\wefeemall\traits\LoginCheck;

class Member extends Base
{
    use LoginCheck;

    public function _initialize()
    {
        parent::_initialize();

        $this->loginCheck();
    }

    public function index()
    {
        $user = AuthManage::user();

        $title = '会员中心';

        return view(VIEW_PATH . '/index/member/index.html', compact('title', 'user'));
    }

}