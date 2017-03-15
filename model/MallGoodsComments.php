<?php namespace addons\wefeemall\model;


class MallGoodsComments extends BaseModel
{

    public function user()
    {
        return $this->belongsTo('addons\wefeemall\model\MallUsers', 'user_id');
    }

    public function goods()
    {
        return $this->belongsTo('addons\wefeemall\model\MallGoods', 'goods_id');
    }

    public function setCommentPhotosAttr($value)
    {
        return serialize($value);
    }

    public function getCommentPhotosAttr($value)
    {
        return unserialize($value);
    }

}