<?php namespace addons\wefeemall\model;

class MallCoupons extends BaseModel
{

    public function categories()
    {
        return $this->belongsToMany('addons\wefeemall\model\MallCategories', full_table('mall_coupon_category'), 'category_id', 'coupon_id');
    }

    public function goods()
    {
        return $this->belongsToMany('addons\wefeemall\model\MallGoods', full_table('mall_coupon_goods'), 'goods_id', 'coupon_id');
    }

    public function children()
    {
        return $this->hasMany('addons\wefeemall\model\MallCouponUsers', 'coupon_id');
    }

}