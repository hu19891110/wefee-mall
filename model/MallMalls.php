<?php namespace addons\wefeemall\model;


class MallMalls extends BaseModel
{

    public function user()
    {
        return $this->belongsTo('addons\wefeemall\model\MallUsers', 'user_id');
    }

    public function goods()
    {
        return $this->belongsTo('addons\wefeemall\model\MallGoods', 'goods_id');
    }

}