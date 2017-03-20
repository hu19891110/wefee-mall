<?php namespace addons\wefeemall\model;

class MallOrderAddress extends BaseModel
{

    public function order()
    {
        return $this->belongsTo('addons\wefeemall\model\MallOrder', 'order_id');
    }

}