<?php namespace addons\wefeemall\controller\index;

use think\Hook;
use addons\wefeemall\lib\AuthManage;
use addons\wefeemall\model\MallGoods;
use addons\wefeemall\model\MallOrders;
use addons\wefeemall\traits\LoginCheck;
use addons\wefeemall\traits\TokenCheck;
use addons\wefeemall\behavior\AfterCreateOrder;

class Order extends Base
{
    use LoginCheck,TokenCheck;

    public function _initialize()
    {
        parent::_initialize();

        $this->loginCheck();

        Hook::add('created-order', [AfterCreateOrder::class]);
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
                $can[] = $coupon;
            }
        }

        return $can;
    }

    public function post()
    {
        $this->tokenCheck();

        $goods = $this->validateRequest();

        $address = $this->validateAddress();

        $coupon = $this->validateCoupon();

        /** 计算原价 */
        $total = 0;
        foreach ($goods as $item) {
            $total += $item['num'] * $item['goods']->goods_now_cost;
        }

        /** 运费 */
        $delCost = $total > mall_config('free_del_inf') ? 0 : mall_config('del_cost');

        /** 现价 */
        $nowCost = $total + $delCost;
        if ($coupon) {
            $nowCost -= $coupon->coupon->coupon_cost;
        }
        $nowCost = $nowCost <= 0 ? 0 : $nowCost;

        /** 创建订单 */
        $data = [
            'orderid'       => date('YmdHis').mt_rand(10,99),
            'user_id'       => AuthManage::id(),
            'origin_charge' => $total,
            'now_charge'    => $nowCost,
            'coupon_id'     => $coupon ? $coupon->id : 0,
            'coupon_cost'   => $coupon ? $coupon->coupon->coupon_cost : 0,
            'del_cost'      => $delCost,
            'order_status'  => $nowCost == 0 ? 5 : 1,
        ];
        $order = new MallOrders;
        $order->save($data);
        ! $order && $this->error('订单创建失败！code:001.');

        /** 关联收货地址 */
        if (
            ! $order->address()->save([
                'contact_name'  => $address->true_name,
                'contact_phone' => $address->contact_phone,
                'address'       => $address->province . $address->city . $address->region . $address->street,
            ])
        ) {
            $order->delete();
            $this->error('创建订单失败！code:002.');
        }

        /** 关联商品 */
        foreach ($goods as $item) {
            $order->goodses()->attach($item['goods']->id, [
                'goods_charge' => $item['goods']->goods_now_cost,
                'goods_num'    => $item['num'],
            ]);
        }

        Hook::listen('created-order', $order);

        /** 无需支付，直接支付成功 */
        if ($order->order_status == 5) {

        }

        /** 跳转支付 */

    }

    protected function validateRequest()
    {
        ! request()->isPost() && $this->error('请求非法。');

        $id = request()->param('goods_id/a');
        $num = request()->param('num/a');

        $box = [];

        foreach ($id as $key => $item) {
            $goods = MallGoods::get($item);

            ! $goods && $this->error('欲购商品不存在', mall_url('index.order/create'));

            /** 库存检测 */
            $goods->goods_stock <= 0 && $this->error('商品《'.$goods->goods_name.'》暂无库存！', mall_url('index.order/create'));

            $box[] = [
                'num' => $num[$key],
                'goods' => $goods,
            ];
        }

        return $box;
    }

    protected function validateCoupon()
    {
        $coupon_id = (int) request()->param('coupon_id');
        $coupon_id = $coupon_id <= 0 ? 0 : $coupon_id;

        $coupon = null;

        if ($coupon_id > 0) {
            $coupon = AuthManage::user()->coupons()->where('id', $coupon_id)->find();

            ! $coupon && $this->error('优惠券不存在！', mall_url('index.order/create'));
        }

        return $coupon;
    }

    protected function validateAddress()
    {
        $address_id = (int) request()->param('address_id');

        $address = AuthManage::user()->address()->where('id', $address_id)->find();

        ! $address && $this->error('地址不存在！', mall_url('index.order/create'));

        return $address;
    }

}