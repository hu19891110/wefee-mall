<?php namespace addons\wefeemall\model;


class MallCategories extends BaseModel
{

    /** 父分类 */
    public function parent()
    {
        return $this->belongsTo(MallCategories::class, 'fid');
    }

    /** 子分类 */
    public function children()
    {
        return $this->hasMany(MallCategories::class, 'fid');
    }

    /** 分类下的商品 */
    public function goods()
    {
        return $this->hasMany(MallGoods::class, 'category_id');
    }

    public function coupons()
    {
        return $this->belongsToMany(MallCoupons::class, full_table('mall_coupon_category'), 'coupon_id', 'category_id');
    }
}