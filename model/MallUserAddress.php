<?php namespace addons\wefeemall\model;


class MallUserAddress extends BaseModel
{

    public function user()
    {
        return $this->belongsTo(MallUsers::class, 'user_id');
    }

}