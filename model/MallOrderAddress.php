<?php namespace addons\wefeemall\model;

class MallOrderAddress extends BaseModel
{

    public function order()
    {
        return $this->belongsTo(MallOrder::class, 'order_id');
    }

}