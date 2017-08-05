<?php namespace addons\wefeemall\model;

class MallUsers extends BaseModel
{

    public function address()
    {
        return $this->hasMany(MallUserAddress::class, 'user_id');
    }

    public function comments()
    {
        return $this->hasMany(MallGoodsComments::class, 'user_id');
    }

    public function feedback()
    {
        return $this->hasMany(MallFeedback::class, 'user_id');
    }

    public function coupons()
    {
        return $this->hasMany(MallCouponUsers::class, 'user_id');
    }

    public function malls()
    {
        return $this->hasMany(MallMalls::class, 'user_id');
    }

    public function orders()
    {
        return $this->hasMany(MallOrders::class, 'user_id');
    }

    public function wechat()
    {
        return $this->hasOne(MallWechatUser::class, 'user_id');
    }

}