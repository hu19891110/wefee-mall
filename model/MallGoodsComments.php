<?php namespace addons\wefeemall\model;


class MallGoodsComments extends BaseModel
{

    public function user()
    {
        return $this->belongsTo('addons\wefeemall\model\MallUser', 'user_id');
    }

    public function goods()
    {
        return $this->belongsTo('addons\wefeemall\model\MallGoods', 'goods_id');
    }

}