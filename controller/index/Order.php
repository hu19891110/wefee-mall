<?php namespace addons\wefeemall\controller\index;

use addons\wefeemall\lib\AuthManage;
use addons\wefeemall\model\MallGoods;
use addons\wefeemall\traits\LoginCheck;

class Order extends Base
{
    use LoginCheck;

    public function _initialize()
    {
        parent::_initialize();

        $this->loginCheck();
    }

    public function create()
    {
        $mall = $this->getMall();

        /** 总价计算 */
        $total = 0;
        foreach ($mall as $item) {
            $total += $item['num'] * $item['goods']->goods_now_cost;
        }

        /** 是否免邮 */
        $free_del_inf = mall_config('free_del_inf');
        $isFreeDel = $total > $free_del_inf ? 0 : mall_config('del_cost');

        $user = AuthManage::user();

        /** 默认地址 */
        $address = $user->address()->order('is_default', 'desc')->find();

        /** 优惠券 */
        $coupons = $this->getCanUseCoupon($mall);

        $title = '下订单';

        return view(VIEW_PATH . '/index/order/create.html', compact('title', 'coupons', 'address', 'user', 'isFreeDel', 'total', 'mall'));
    }

    /** 获取提交来的购物车信息 */
    protected function getMall()
    {
        if (! request()->isPost() && session('mall')) {
            return session('mall');
        }

        $id  = request()->param('id/a');
        $num = request()->param('num/a');

        if (! $id || ! $num) {
            $this->error('参数错误');
        }

        $mall = [];

        foreach ($id as $key => $item) {
            $goods = MallGoods::get($item);

            !$goods && $this->error('商品不存在');

            $mall[] = [
                'num' => $num[$key],
                'goods' => $goods
            ];
        }

        session('mall', $mall);

        return $mall;
    }

    /**
     * 在当前购物车下，可以使用的优惠券
     * 1.有分类限制的所有商品必须同分类
     * 2.无分类，无商品限制
     */
    public function getCanUseCoupon(array $mall)
    {
        $total = 0;
        foreach ($mall as $item) {
            $total += $item['num'] * $item['goods']->goods_now_cost;
        }

        $coupons = AuthManage::user()->coupons()->where('status', 1)->select();

        $can = [];

        foreach ($coupons as $coupon) {
            if ($total > $coupon->coupon->coupon_inf) {
                $can[] = $coupon->coupon;
            }
        }

        return $can;
    }

}