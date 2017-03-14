<?php namespace addons\wefeemall\controller\api;

use addons\wefeemall\lib\AuthManage;
use addons\wefeemall\model\MallCategories;
use addons\wefeemall\model\MallCoupons;
use addons\wefeemall\model\MallCouponUsers;
use addons\wefeemall\model\MallGoods;
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

    public function receive()
    {
        $coupon = MallCoupons::get(request()->param('coupon_id'));

        if (! $coupon) {
            $this->error('优惠券不存在');
        }

        if ($coupon->children()->where('user_id', AuthManage::id())->find()) {
            $this->error('请不要重复领取！');
        }

        $tmp = new MallCouponUsers;
        $tmp->save([
            'coupon_id' => $coupon->id,
            'user_id' => AuthManage::id(),
        ]);

        $this->success('领取成功！');
    }

}