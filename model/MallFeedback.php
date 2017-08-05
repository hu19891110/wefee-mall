<?php namespace addons\wefeemall\model;

class MallFeedback extends BaseModel
{

    public function user()
    {
        return $this->belongsTo(MallUsers::class, 'user_id');
    }

}