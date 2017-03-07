<?php namespace addons\wefeemall\model;


class MallGoods extends BaseModel
{

    public function category()
    {
        return $this->belongsTo('addons\wefeemall\model\MallCategories', 'category_id');
    }

    public function comments()
    {
        return $this->hasMany('addons\wefeemall\model\MallGoodsComments', 'goods_id');
    }

    public function coupons()
    {
        return $this->belongsToMany('addons\wefeemall\model\MallCoupons', full_table('mall_coupon_goods'), 'coupon_id', 'goods_id');
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

}