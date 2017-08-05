<?php namespace addons\wefeemall\model;


class MallMalls extends BaseModel
{

    public function user()
    {
        return $this->belongsTo(MallUsers::class, 'user_id');
    }

    public function goods()
    {
        return $this->belongsTo(MallGoods::class, 'goods_id');
    }

}