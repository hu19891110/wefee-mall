<?php namespace addons\wefeemall\model;


class MallGoodsComments extends BaseModel
{

    public function user()
    {
        return $this->belongsTo(MallUsers::class, 'user_id');
    }

    public function goods()
    {
        return $this->belongsTo(MallGoods::class, 'goods_id');
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