<?php namespace addons\wefeemall\model;


class MallUsers extends BaseModel
{

    public function address()
    {
        return $this->hasMany('addons\wefeemall\model\MallUserAddress', 'user_id');
    }

    public function comments()
    {
        return $this->hasMany('addons\wefeemall\model\modelMailGoodsComments', 'user_id');
    }

}