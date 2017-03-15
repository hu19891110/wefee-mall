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

    public function feedback()
    {
        return $this->hasMany('addons\wefeemall\model\MallFeedback', 'user_id');
    }

    public function coupons()
    {
        return $this->hasMany('addons\wefeemall\model\MallCouponUsers', 'user_id');
    }

    public function malls()
    {
        return $this->hasMany('addons\wefeemall\model\MallMalls', 'user_id');
    }

}