<?php namespace addons\wefeemall\controller\api;

use addons\wefeemall\lib\AuthManage;
use addons\wefeemall\traits\LoginCheck;
use think\Controller;

class Coupon extends Controller
{

    use LoginCheck;

    public function _initialize()
    {
        parent::_initialize();

        $this->loginCheck();
    }

    public function member()
    {
        $where = [];
        if (request()->param('coupon_status') != '') {
            $where['status'] = ['=', request()->param('coupon_status')];
        }

        $user = AuthManage::user();

        $coupons = $user->coupons()->where($where)->select();
        
        $arr = [];

        foreach ($coupons as $coupon) {
            $arr[] = $coupon->coupon->toArray();
        }

        return json($arr);
    }

}