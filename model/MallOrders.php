<?php namespace addons\wefeemall\model;

class MallOrders extends BaseModel
{

    public function user()
    {
        return $this->belongsTo('addons\wefeemall\model\MallUsers', 'user_id');
    }

    public function address()
    {
        return $this->hasOne('addons\wefeemall\model\MallOrderAddress', 'order_id');
    }

    public function goodses()
    {
        return $this->belongsToMany('addons\wefeemall\model\MallGoods', full_table('mall_order_goods'), 'goods_id', 'order_id');
    }

}