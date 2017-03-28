<?php namespace addons\wefeemall\transform;

use addons\wefeemall\model\MallOrders;

class OrderTransformer
{

    public function transform(MallOrders $order)
    {
        $goods = [];
        foreach ($order->goodses as $item) {
            $goods[] = (new GoodsTransformer())->transform($item);
        }

        return [
            'id'           => $order->id,
            'orderid'      => $order->orderid,
            'user'         => (new UserTransformer())->transform($order->user),
            'origin_charge'=> $order->origin_charge,
            'now_charge'   => $order->now_charge,
            'coupon_cost'  => $order->coupon_cost,
            'del_cost'     => $order->del_cost,
            'order_status' => $order->order_status,
            'created_at'   => $order->created_at,
            'address'      => [
                'contact_name'  => $order->address->contact_name,
                'contact_phone' => $order->address->contact_phone,
                'address'       => $order->address->address,
                'del_number'    => $order->address->del_number,
                'del_company'   => $order->address->del_company,
            ],
            'goods' => $goods,
        ];
    }

}