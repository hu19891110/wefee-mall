<?php namespace addons\wefeemall\model;


class MallCategories extends BaseModel
{

    /** 父分类 */
    public function parent()
    {
        return $this->belongsTo('addons\wefeemall\model\MallCategories', 'fid');
    }

    /** 子分类 */
    public function children()
    {
        return $this->hasMany('addons\wefeemall\model\MallCategories', 'fid');
    }

    /** 分类下的商品 */
    public function goods()
    {
        return $this->hasMany('addons\wefeemall\model\MallGoods', 'category_id');
    }

}