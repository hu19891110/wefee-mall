<?php namespace addons\wefeemall\transform;

use addons\wefeemall\model\MallGoods;

class GoodsTransformer
{

    public function transform(MallGoods $goods)
    {
        return [
            'id'                => $goods->id,
            'goods_name'        => $goods->goods_name,
            'goods_photos'      => $goods->goods_photos,
            'goods_stock'       => $goods->goods_stock,
            'goods_origin_cost' => $goods->goods_origin_cost,
            'goods_now_cost'    => $goods->goods_now_cost,
            'goods_score'       => $goods->goods_score,
            'goods_sales'       => $goods->goods_sales,
        ];
    }
}