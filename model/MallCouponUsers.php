<?php namespace addons\wefeemall\model;

class MallCouponUsers extends BaseModel
{

    protected $autoWriteTimestamp = false;

    public function coupon()
    {
        return $this->belongsTo(MallCoupons::class, 'coupon_id');
    }

    public function user()
    {
        return $this->belongsTo(MallUsers::class, 'user_id');
    }

}