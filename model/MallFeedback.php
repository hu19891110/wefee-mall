<?php namespace addons\wefeemall\model;

class MallFeedback extends BaseModel
{

    public function user()
    {
        return $this->belongsTo('addons\wefeemall\model\MallUsers', 'user_id');
    }

}