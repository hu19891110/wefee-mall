<?php namespace addons\wefeemall\model;


class MallGoods extends BaseModel
{

    public function category()
    {
        return $this->belongsTo(MallCategories::class, 'category_id');
    }

    public function comments()
    {
        return $this->hasMany(MallGoodsComments::class, 'goods_id');
    }

    public function coupons()
    {
        return $this->belongsToMany(MallCoupons::class, full_table('mall_coupon_goods'), 'coupon_id', 'goods_id');
    }

    public function setGoodsPhotosAttr($value)
    {
        return serialize($value);
    }

    public function getGoodsPhotosAttr($value)
    {
        return unserialize($value);
    }

    public function setGoodsAdditionParamsAttr($value)
    {
        return serialize($value);
    }

    public function getGoodsAdditionParamsAttr($value)
    {
        return unserialize($value);
    }

    public function malls()
    {
        return $this->hasMany(MallMalls::class, 'goods_id');
    }

    public function orders()
    {
        return $this->belongsToMany(MallOrders::class, full_table('mall_order_goods'), 'order_id', 'goods_id');
    }

}