<?php namespace addons\wefeemall\model;


class MallUserAddress extends BaseModel
{

    public function user()
    {
        return $this->belongsTo('addons\wefeemall\model\MallUsers', 'user_id');
    }

}