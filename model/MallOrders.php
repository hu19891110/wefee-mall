<?php namespace addons\wefeemall\model;

class MallOrders extends BaseModel
{

    public function user()
    {
        return $this->belongsTo(MallUsers::class, 'user_id');
    }

    public function coupon()
    {
        return $this->hasOne(MallCouponUsers::class, 'coupon_id');
    }

    public function address()
    {
        return $this->hasOne(MallOrderAddress::class, 'order_id');
    }

    public function goodses()
    {
        return $this->belongsToMany(MallGoods::class, full_table('mall_order_goods'), 'goods_id', 'order_id');
    }

}