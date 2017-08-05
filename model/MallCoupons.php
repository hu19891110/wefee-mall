<?php namespace addons\wefeemall\model;

class MallCoupons extends BaseModel
{

    public function categories()
    {
        return $this->belongsToMany(MallCategories::class, full_table('mall_coupon_category'), 'category_id', 'coupon_id');
    }

    public function goods()
    {
        return $this->belongsToMany(MallGoods::class, full_table('mall_coupon_goods'), 'goods_id', 'coupon_id');
    }

    public function children()
    {
        return $this->hasMany(MallCouponUsers::class, 'coupon_id');
    }

}