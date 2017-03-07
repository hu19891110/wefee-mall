<?php namespace addons\wefeemall\model;

class MallCouponUsers extends BaseModel
{

    protected $autoWriteTimestamp = false;

    public function coupon()
    {
        return $this->belongsTo('addons\wefeemall\model\MallCoupons', 'coupon_id');
    }

    public function user()
    {
        return $this->belongsTo('addons\wefeemall\model\MallUsers', 'user_id');
    }

}