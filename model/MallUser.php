<?php namespace addons\wefeemall\model;


class MallUser extends BaseModel
{

    public function address()
    {
        return $this->hasMany('addons\wefeemall\model\MailUserAddress', 'user_id');
    }

    public function comments()
    {
        return $this->hasMany('addons\wefeemall\modelMailGoodsComments', 'user_id');
    }

}