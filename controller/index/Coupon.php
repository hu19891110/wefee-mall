<?php namespace addons\wefeemall\controller\index;

use addons\wefeemall\lib\AuthManage;
use addons\wefeemall\traits\LoginCheck;

class Coupon extends Base
{
    use LoginCheck;

    public function _initialize()
    {
        parent::_initialize();

        $this->loginCheck();
    }

    public function index()
    {

    }

    public function member()
    {
        $user = AuthManage::user();

        $title = '我的优惠券';

        return view(VIEW_PATH . '/index/coupon/member.html', compact('title', 'user'));
    }

}