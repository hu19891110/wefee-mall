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