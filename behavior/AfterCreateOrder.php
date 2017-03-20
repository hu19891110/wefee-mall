<?php namespace addons\wefeemall\behavior;

/**
 * 创建订单之后的动作
 * @author Qsnh <616896861@qq.com>
 */

use addons\wefeemall\lib\AuthManage;
use addons\wefeemall\model\MallCouponUsers;
use addons\wefeemall\model\MallGoods;
use addons\wefeemall\model\MallOrderGoods;
use addons\wefeemall\model\MallOrders;

class AfterCreateOrder
{

    public function run(MallOrders &$params)
    {
        /** 优惠券处理 */
        if ($params->coupon_id != 0) {
            MallCouponUsers::where('id', $params->coupon_id)->update(['status' => 3]);
        }

        foreach ($params->goodses as $item) {
            /** 购物车处理 */
            $tmp = AuthManage::user()->malls()->where('goods_id', $item->id)->find();
            if ($tmp) {
                $tmp->delete();
            }

            /** 商品库存 */
            $orderGoods = MallOrderGoods::where([
                'order_id' => $params->id,
                'goods_id' => $item->id,
            ])->find();
            if ($orderGoods) {
                MallGoods::where('id', $item->id)->setDec('goods_stock', $orderGoods->goods_num);
            }
        }
    }

}