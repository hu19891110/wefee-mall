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

}